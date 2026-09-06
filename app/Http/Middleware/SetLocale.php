<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $allowed = config('nexvary.languages', ['en']);
        $requested = (string) $request->query('lang', '');

        if ($requested !== '' && in_array($requested, $allowed, true)) {
            $request->session()->put('locale', $requested);
        }

        $locale = (string) $request->session()->get('locale', config('app.locale', 'en'));

        if (! in_array($locale, $allowed, true)) {
            $locale = 'en';
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
