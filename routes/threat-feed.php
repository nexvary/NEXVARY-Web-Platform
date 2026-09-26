<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

// Optional geolocated observation provider. Never synthesize incidents or paths.
Route::get('/api/threat-feed', function () {
    $provider = trim((string) env('NEXVARY_THREAT_FEED_URL', ''));
    if ($provider === '' || str_starts_with($provider, 'https://') === false) {
        return response()->json(['mode' => 'unavailable', 'events' => [], 'source' => null])
            ->header('Cache-Control', 'no-store');
    }

    try {
        $payload = Cache::remember('nexvary.geo-observations.v2', now()->addMinutes(5), function () use ($provider) {
            $request = Http::acceptJson()->timeout(7);
            if ($token = env('NEXVARY_THREAT_FEED_TOKEN')) {
                $request = $request->withToken((string) $token);
            }

            $response = $request->get($provider);
            $response->throw();
            $json = $response->json();
            if (is_array($json) === false || is_array($json['events'] ?? null) === false || is_string($json['source'] ?? null) === false) {
                throw new RuntimeException('Geolocated feed needs events and source fields');
            }
            $events = collect($json['events'])->filter(fn ($event) => is_array($event) && is_numeric($event['lat'] ?? null) && is_numeric($event['lon'] ?? null) && abs((float) $event['lat']) <= 90 && abs((float) $event['lon']) <= 180 && is_string($event['label'] ?? null))->take(40)->map(fn ($event) => [
                'id' => mb_substr((string) ($event['id'] ?? ''), 0, 80),
                'type' => in_array($event['type'] ?? '', ['attack', 'scan', 'infrastructure'], true) ? $event['type'] : 'scan',
                'label' => mb_substr($event['label'], 0, 140),
                'city' => mb_substr((string) ($event['city'] ?? ''), 0, 90),
                'lat' => (float) $event['lat'],
                'lon' => (float) $event['lon'],
                'severity' => in_array($event['severity'] ?? '', ['high', 'medium', 'low'], true) ? $event['severity'] : 'low',
            ])->values()->all();

            return ['source' => mb_substr($json['source'], 0, 100), 'observed_at' => (string) ($json['observed_at'] ?? ''), 'events' => $events];
        });

        return response()->json(['mode' => 'live', 'source' => $payload['source'], 'observed_at' => $payload['observed_at'], 'events' => $payload['events']])
            ->header('Cache-Control', 'public, max-age=60');
    } catch (Throwable $exception) {
        report($exception);

        return response()->json(['mode' => 'unavailable', 'events' => [], 'source' => null])
            ->header('Cache-Control', 'no-store');
    }
})->middleware('throttle:public-health')->name('threat-feed.public');
