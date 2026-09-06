<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $allowed = config('nexvary.languages', ['en']);
        $locale = (string) $request->session()->get('locale', config('app.locale', 'en'));

        if (! in_array($locale, $allowed, true)) {
            $locale = 'en';
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
