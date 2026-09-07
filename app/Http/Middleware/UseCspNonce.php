<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Vite;
use Symfony\Component\HttpFoundation\Response;

final class UseCspNonce
{
    public function handle(Request $request, Closure $next): Response
    {
        Vite::useCspNonce();

        return $next($request);
    }
}
