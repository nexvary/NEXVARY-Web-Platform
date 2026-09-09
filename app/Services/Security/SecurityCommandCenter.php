<?php

namespace App\Services\Security;

use App\Services\Security\Contracts\SecuritySignalSource;
use App\Services\Security\Sources\CorazaSignalSource;
use App\Services\Security\Sources\CrowdSecSignalSource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

final class SecurityCommandCenter
{
    public function __construct(private readonly SecuritySignalAggregator $aggregator) {}

    /** @return array<string, mixed> */
    public function snapshot(): array
    {
        $sources = $this->sources();
        $signals = $this->internalSignals();

        foreach ($sources as $source) {
            $signals = array_merge($signals, $source->signals());
        }

        $normalized = collect($this->aggregator->normalize($signals))
            ->sortByDesc('score')
            ->take(80)
            ->values();

        $maxScore = (int) ($normalized->max('score') ?? 0);

        return [
            'riskScore' => $maxScore,
            'riskLevel' => $this->level($maxScore),
            'signalCount' => $normalized->count(),
            'criticalCount' => $normalized->where('level', 'critical')->count(),
            'highCount' => $normalized->whereIn('level', ['high', 'critical'])->count(),
            'signals' => $normalized,
            'sources' => collect($sources)->map(fn (SecuritySignalSource $source): array => $source->health())->values(),
        ];
    }

    /** @return array<int, SecuritySignalSource> */
    private function sources(): array
    {
        $waf = config('security_command.sources.waf', []);
        $crowd = config('security_command.sources.crowd', []);

        return [
            new CorazaSignalSource(
                'waf',
                (bool) ($waf['enabled'] ?? false),
                $waf['endpoint'] ?? null,
                env('NEXVARY_WAF_TOKEN'),
            ),
            new CrowdSecSignalSource(
                'crowd',
                (bool) ($crowd['enabled'] ?? false),
                $crowd['endpoint'] ?? null,
                env('NEXVARY_CROWD_TOKEN'),
            ),
        ];
    }

    /** @return array<int, array<string, mixed>> */
    private function internalSignals(): array
    {
        if (! Schema::hasTable('security_audit_events')) {
            return [];
        }

        return DB::table('security_audit_events')
            ->latest('created_at')
            ->limit(40)
            ->get(['id', 'event', 'method', 'route', 'created_at'])
            ->map(function (object $row): array {
                $event = strtolower((string) $row->event);
                $sensitive = str_contains($event, 'failed') || str_contains($event, 'denied') || str_contains($event, 'security');

                return [
                    'id' => (string) $row->id,
                    'source' => 'application',
                    'vector' => (string) $row->event,
                    'severity' => $sensitive ? 6 : 2,
                    'confidence' => 1.0,
                    'exposure' => 0.4,
                    'repeated' => false,
                    'occurred_at' => (string) $row->created_at,
                    'metadata' => [
                        'method' => (string) $row->method,
                        'route' => (string) $row->route,
                    ],
                ];
            })
            ->all();
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
}
