<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureAppCenter
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        abort_unless($user !== null, 403);

        $role = (string) ($user->app_center_role ?? '');
        $siteRole = (string) ($user->role ?? '');
        $allowed = $siteRole === 'owner' || in_array($role, ['admin', 'assistant'], true);

        abort_unless($allowed, 403);

        return $next($request);
    }
}
