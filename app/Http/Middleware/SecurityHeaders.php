<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $policy = implode('; ', [
            'default-src \'self\'',
            'base-uri \'self\'',
            'object-src \'none\'',
            'frame-ancestors \'none\'',
            'form-action \'self\'',
            'img-src \'self\' data: https:',
            'font-src \'self\' data:',
            'style-src \'self\' \'unsafe-inline\'',
            'script-src \'self\'',
            'connect-src \'self\' https://www.virustotal.com',
            'upgrade-insecure-requests',
        ]);

        $response->headers->set('Content-Security-Policy', $policy);
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=()');
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');
        $response->headers->set('Cross-Origin-Resource-Policy', 'same-origin');
        $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');

        $adminPrefix = trim((string) config('nexvary.admin_prefix'), '/');
        $authPrefix = trim((string) config('fortify.prefix', 'secure-access'), '/');
        $isPrivateSurface = $request->is($adminPrefix, $adminPrefix.'/*', $authPrefix, $authPrefix.'/*');

        if ($isPrivateSurface) {
            $response->headers->set('X-Robots-Tag', 'noindex, nofollow, noarchive, nosnippet');
            $response->headers->set('Cache-Control', 'no-store, private, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
        }

        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        return $response;
    }
}
