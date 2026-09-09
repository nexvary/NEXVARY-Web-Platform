<?php

namespace App\Services\Security;

final class SecuritySignalAggregator
{
    /**
     * Normalize heterogeneous security events into one command-center schema.
     *
     * @param  array<int, array<string, mixed>>  $events
     * @return array<int, array<string, mixed>>
     */
    public function normalize(array $events): array
    {
        return array_values(array_map(function (array $event): array {
            $severity = $this->clamp((float) ($event['severity'] ?? 0), 0, 10);
            $confidence = $this->clamp((float) ($event['confidence'] ?? 0.5), 0, 1);
            $exposure = $this->clamp((float) ($event['exposure'] ?? 0.5), 0, 1);
            $repeatBoost = ! empty($event['repeated']) ? 12 : 0;
            $score = (int) round($this->clamp(($severity * 7.2 * $confidence) + ($exposure * 16) + $repeatBoost, 0, 100));

            return [
                'id' => (string) ($event['id'] ?? bin2hex(random_bytes(8))),
                'source' => (string) ($event['source'] ?? 'application'),
                'vector' => (string) ($event['vector'] ?? 'unknown'),
                'location' => $event['location'] ?? null,
                'score' => $score,
                'level' => $this->level($score),
                'occurred_at' => (string) ($event['occurred_at'] ?? now()->toIso8601String()),
                'metadata' => (array) ($event['metadata'] ?? []),
            ];
        }, $events));
    }

    private function level(int $score): string
    {
        return match (true) {
            $score >= 85 => 'critical',
            $score >= 70 => 'high',
            $score >= 45 => 'elevated',
            $score >= 20 => 'guarded',
            default => 'low',
        };
    }

    private function clamp(float $value, float $min, float $max): float
    {
        return min($max, max($min, $value));
    }
}
