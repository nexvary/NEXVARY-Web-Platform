<?php

declare(strict_types=1);

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/api/threat-feed', function () {
    $provider = trim((string) env('NEXVARY_THREAT_FEED_URL', ''));

    if ($provider !== '') {
        try {
            $payload = Cache::remember('nexvary.threat-feed', 15, function () use ($provider): array {
                $response = Http::acceptJson()->timeout(5)->retry(1, 150)->get($provider);
                $response->throw();
                $json = $response->json();

                if (! is_array($json) || ! isset($json['events']) || ! is_array($json['events'])) {
                    throw new RuntimeException('Threat feed provider must return an events array.');
                }

                return $json;
            });

            return response()->json([
                'mode' => 'live',
                'updated_at' => now()->toIso8601String(),
                'events' => array_slice($payload['events'], 0, 80),
            ])->header('Cache-Control', 'public, max-age=10, stale-while-revalidate=20');
        } catch (ConnectionException|Throwable $exception) {
            report($exception);
        }
    }

    $seed = (int) floor(now()->timestamp / 30);
    $events = [
        ['id' => "{$seed}-1", 'type' => 'attack', 'label' => 'Suspicious traffic', 'city' => 'Frankfurt, DE', 'lat' => 50.11, 'lon' => 8.68, 'severity' => 'high'],
        ['id' => "{$seed}-2", 'type' => 'scan', 'label' => 'Reconnaissance', 'city' => 'Singapore, SG', 'lat' => 1.35, 'lon' => 103.82, 'severity' => 'medium'],
        ['id' => "{$seed}-3", 'type' => 'infrastructure', 'label' => 'New infrastructure', 'city' => 'Virginia, US', 'lat' => 37.43, 'lon' => -78.65, 'severity' => 'low'],
        ['id' => "{$seed}-4", 'type' => 'attack', 'label' => 'Malware telemetry', 'city' => 'Tokyo, JP', 'lat' => 35.68, 'lon' => 139.69, 'severity' => 'high'],
        ['id' => "{$seed}-5", 'type' => 'infrastructure', 'label' => 'Certificate activity', 'city' => 'Amsterdam, NL', 'lat' => 52.37, 'lon' => 4.90, 'severity' => 'low'],
        ['id' => "{$seed}-6", 'type' => 'scan', 'label' => 'Internet scan', 'city' => 'Dubai, AE', 'lat' => 25.20, 'lon' => 55.27, 'severity' => 'medium'],
        ['id' => "{$seed}-7", 'type' => 'attack', 'label' => 'Brute-force telemetry', 'city' => 'São Paulo, BR', 'lat' => -23.55, 'lon' => -46.63, 'severity' => 'high'],
    ];

    return response()->json([
        'mode' => 'simulated',
        'updated_at' => now()->toIso8601String(),
        'events' => $events,
    ])->header('Cache-Control', 'no-store');
})->middleware('throttle:public-health')->name('threat-feed.public');
