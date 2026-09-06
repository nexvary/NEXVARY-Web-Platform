<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;

Artisan::command('security:audit-verify', function (): int {
    $key = (string) config('app.key');
    $rows = DB::table('security_audit_events')->orderBy('created_at')->orderBy('id')->get();
    $expectedPrevious = null;

    foreach ($rows as $row) {
        if ($row->previous_hash !== $expectedPrevious) {
            $this->error("Audit chain break before event {$row->id}.");

            return 1;
        }

        $payload = [
            'actor_user_id' => $row->actor_user_id,
            'event' => $row->event,
            'method' => $row->method,
            'route' => $row->route,
            'ip_hash' => $row->ip_hash,
            'user_agent_hash' => $row->user_agent_hash,
            'metadata' => json_decode((string) $row->metadata, true, flags: JSON_THROW_ON_ERROR),
            'previous_hash' => $row->previous_hash,
            'created_at' => \Illuminate\Support\Carbon::parse($row->created_at)->toISOString(),
        ];

        $expectedHash = hash_hmac('sha256', json_encode($payload, JSON_THROW_ON_ERROR), $key);

        if (! hash_equals((string) $row->event_hash, $expectedHash)) {
            $this->error("Audit integrity failure at event {$row->id}.");

            return 1;
        }

        $expectedPrevious = $row->event_hash;
    }

    $this->info('Security audit chain verified: '.$rows->count().' event(s).');

    return 0;
})->purpose('Verify the tamper-evident security audit chain');

Schedule::command('model:prune')->daily();
