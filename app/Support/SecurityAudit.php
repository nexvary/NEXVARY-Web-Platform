<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class SecurityAudit
{
    /**
     * @param  array<string, mixed>  $metadata
     */
    public static function record(Request $request, string $event, array $metadata = []): void
    {
        $key = (string) config('app.key');
        $now = now();

        DB::transaction(function () use ($request, $event, $metadata, $key, $now): void {
            $previousHash = DB::table('security_audit_events')
                ->orderByDesc('created_at')
                ->orderByDesc('id')
                ->lockForUpdate()
                ->value('event_hash');

            $payload = [
                'actor_user_id' => $request->user()?->getAuthIdentifier(),
                'event' => $event,
                'method' => strtoupper($request->method()),
                'route' => $request->route()?->getName() ?? $request->path(),
                'ip_hash' => $request->ip() ? hash_hmac('sha256', $request->ip(), $key) : null,
                'user_agent_hash' => $request->userAgent() ? hash_hmac('sha256', $request->userAgent(), $key) : null,
                'metadata' => $metadata,
                'previous_hash' => $previousHash,
                'created_at' => $now->toISOString(),
            ];

            $eventHash = hash_hmac('sha256', json_encode($payload, JSON_THROW_ON_ERROR), $key);

            DB::table('security_audit_events')->insert([
                'id' => (string) Str::ulid(),
                'actor_user_id' => $payload['actor_user_id'],
                'event' => $payload['event'],
                'method' => $payload['method'],
                'route' => $payload['route'],
                'ip_hash' => $payload['ip_hash'],
                'user_agent_hash' => $payload['user_agent_hash'],
                'metadata' => json_encode($metadata, JSON_THROW_ON_ERROR),
                'previous_hash' => $previousHash,
                'event_hash' => $eventHash,
                'created_at' => $now,
            ]);
        }, 3);
    }
}
