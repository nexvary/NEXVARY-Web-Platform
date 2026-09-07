<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Support\SecurityAudit;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class AuditAdminActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->user()?->is_admin) {
            SecurityAudit::record($request, 'admin.request', [
                'status' => $response->getStatusCode(),
            ]);
        }

        return $response;
    }
}
