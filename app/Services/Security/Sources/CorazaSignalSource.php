<?php

namespace App\Services\Security\Sources;

final class CorazaSignalSource extends JsonHttpSecuritySource
{
    protected function mapPayload(mixed $payload): array
    {
        $rows = is_array($payload) ? ($payload['events'] ?? $payload['items'] ?? $payload) : [];
        if (! is_array($rows)) {
            return [];
        }

        return array_values(array_filter(array_map(function (mixed $row): ?array {
            if (! is_array($row)) {
                return null;
            }

            $severity = match (strtolower((string) ($row['severity'] ?? 'notice'))) {
                'critical', 'emergency' => 10,
                'error', 'high' => 8,
                'warning', 'medium' => 6,
                'notice', 'low' => 3,
                default => 2,
            };

            return [
                'id' => (string) ($row['id'] ?? $row['transaction_id'] ?? ''),
                'source' => 'waf',
                'vector' => (string) ($row['rule_id'] ?? $row['message'] ?? 'waf-rule'),
                'location' => $row['client_ip'] ?? $row['country'] ?? null,
                'severity' => $severity,
                'confidence' => isset($row['confidence']) ? (float) $row['confidence'] : 0.85,
                'exposure' => 0.8,
                'repeated' => (bool) ($row['repeated'] ?? false),
                'occurred_at' => (string) ($row['timestamp'] ?? $row['created_at'] ?? now()->toIso8601String()),
                'metadata' => [
                    'uri' => $row['uri'] ?? null,
                    'method' => $row['method'] ?? null,
                    'action' => $row['action'] ?? null,
                ],
            ];
        }, $rows)));
    }
}
