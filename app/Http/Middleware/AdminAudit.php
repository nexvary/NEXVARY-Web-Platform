<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\AuditLog;
use Closure;
use Illuminate\Http\Request;
use Throwable;
use Symfony\Component\HttpFoundation\Response;

final class AdminAudit
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        try {
            AuditLog::query()->create([
                'user_id' => $request->user()?->getAuthIdentifier(),
                'method' => $request->method(),
                'path' => '/'.ltrim($request->path(), '/'),
                'ip_hash' => $request->ip() ? hash('sha256', $request->ip()) : null,
                'user_agent_hash' => $request->userAgent() ? hash('sha256', $request->userAgent()) : null,
                'status_code' => $response->getStatusCode(),
                'created_at' => now(),
            ]);
        } catch (Throwable $exception) {
            report($exception);
        }

        return $response;
    }
}
