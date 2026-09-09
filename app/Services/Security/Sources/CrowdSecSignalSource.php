<?php

namespace App\Services\Security\Sources;

final class CrowdSecSignalSource extends JsonHttpSecuritySource
{
    protected function mapPayload(mixed $payload): array
    {
        $rows = is_array($payload) ? ($payload['alerts'] ?? $payload['decisions'] ?? $payload) : [];
        if (! is_array($rows)) {
            return [];
        }

        return array_values(array_filter(array_map(function (mixed $row): ?array {
            if (! is_array($row)) {
                return null;
            }

            $scenario = (string) ($row['scenario'] ?? $row['type'] ?? 'behavioral-threat');
            $scope = (string) ($row['scope'] ?? 'ip');
            $value = $row['value'] ?? $row['source']['ip'] ?? null;
            $duration = (string) ($row['duration'] ?? '');
            $repeated = str_contains(strtolower($scenario), 'bruteforce') || str_contains(strtolower($scenario), 'scan');

            return [
                'id' => (string) ($row['id'] ?? ''),
                'source' => 'crowd',
                'vector' => $scenario,
                'location' => $value,
                'severity' => $repeated ? 8 : 6,
                'confidence' => 0.9,
                'exposure' => $scope === 'ip' ? 0.7 : 0.55,
                'repeated' => $repeated,
                'occurred_at' => (string) ($row['created_at'] ?? $row['start_at'] ?? now()->toIso8601String()),
                'metadata' => [
                    'scope' => $scope,
                    'duration' => $duration,
                    'origin' => $row['origin'] ?? null,
                ],
            ];
        }, $rows)));
    }
}
