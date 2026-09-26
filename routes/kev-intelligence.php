<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

// CISA's official, CC0 KEV mirror. This catalog identifies exploited CVEs;
// it does not contain geolocation, attack paths, or NEXVARY customer telemetry.
Route::get('/api/security-intelligence', function () {
    $key = 'nexvary.cisa-kev.v1';
    $snapshot = Cache::get($key);
    $stale = false;

    if (! is_array($snapshot) || now()->timestamp - ($snapshot['fetched_at'] ?? 0) > 21600) {
        try {
            $response = Http::acceptJson()->timeout(8)->get('https://raw.githubusercontent.com/cisagov/kev-data/develop/known_exploited_vulnerabilities.json');
            $response->throw();
            $catalog = $response->json();
            if (! is_array($catalog) || ! is_array($catalog['vulnerabilities'] ?? null) || ! is_string($catalog['dateReleased'] ?? null)) {
                throw new RuntimeException('Invalid CISA KEV catalog response');
            }
            $entries = collect($catalog['vulnerabilities'])->filter(fn ($entry) => is_array($entry) &&
                preg_match('/^CVE-\d{4}-\d{4,19}$/', (string) ($entry['cveID'] ?? '')) &&
                preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) ($entry['dateAdded'] ?? '')))
                ->sortByDesc('dateAdded')->take(8)->map(fn ($entry) => [
                    'cve' => $entry['cveID'],
                    'date_added' => $entry['dateAdded'],
                    'vendor' => mb_substr((string) ($entry['vendorProject'] ?? ''), 0, 90),
                    'product' => mb_substr((string) ($entry['product'] ?? ''), 0, 90),
                    'name' => mb_substr((string) ($entry['vulnerabilityName'] ?? ''), 0, 180),
                    'ransomware' => ($entry['knownRansomwareCampaignUse'] ?? '') === 'Known',
                ])->values()->all();
            if (count($entries) < 1) throw new RuntimeException('Empty CISA KEV catalog');
            $snapshot = [
                'fetched_at' => now()->timestamp,
                'catalog_released_at' => $catalog['dateReleased'],
                'catalog_count' => (int) ($catalog['count'] ?? count($catalog['vulnerabilities'])),
                'entries' => $entries,
            ];
            Cache::put($key, $snapshot, now()->addDay());
        } catch (Throwable $exception) {
            report($exception);
            $stale = is_array($snapshot);
        }
    }

    if (! is_array($snapshot)) {
        return response()->json(['status' => 'unavailable', 'source' => 'CISA KEV', 'entries' => []], 503)
            ->header('Cache-Control', 'no-store');
    }

    return response()->json([
        'status' => $stale ? 'stale' : 'current',
        'source' => 'CISA KEV',
        'source_url' => 'https://www.cisa.gov/known-exploited-vulnerabilities-catalog',
        'catalog_released_at' => $snapshot['catalog_released_at'],
        'catalog_count' => $snapshot['catalog_count'],
        'entries' => $snapshot['entries'],
    ])->header('Cache-Control', 'public, max-age=300');
})->middleware('throttle:public-health')->name('intelligence.public');
