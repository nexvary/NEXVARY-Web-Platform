<?php

declare(strict_types=1);

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

Route::get('/', fn () => Inertia::render('home'))->name('home');
Route::get('/services', fn () => Inertia::render('services'))->name('services');
Route::get('/about', fn () => Inertia::render('about'))->name('about');
Route::get('/apps', fn () => Inertia::render('apps'))->name('apps');
Route::get('/safescan', fn () => Inertia::render('safescan'))->name('safescan');

Route::post('/locale/{locale}', function (Request $request, string $locale): RedirectResponse {
    abort_unless(in_array($locale, config('nexvary.languages', ['en']), true), 404);
    $request->session()->put('locale', $locale);

    return back();
})->middleware('throttle:locale')->name('locale.update');

Route::get('/health', fn () => response()->json(['status' => 'ok']))
    ->middleware('throttle:public-health')
    ->name('health.public');

Route::get('/robots.txt', function (): Response {
    $admin = trim((string) config('nexvary.admin_prefix'), '/');
    $body = "User-agent: *\nDisallow: /{$admin}/\nDisallow: /health\nSitemap: https://nexvary.com/sitemap.xml\n";

    return response($body, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
})->name('robots');

Route::get('/sitemap.xml', function (): Response {
    $urls = ['/', '/services', '/apps', '/safescan', '/about'];
    $items = collect($urls)->map(fn (string $path): string => '<url><loc>'.e('https://nexvary.com'.$path).'</loc></url>')->implode('');
    $xml = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.$items.'</urlset>';

    return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
})->name('sitemap');

Route::prefix(config('nexvary.admin_prefix'))
    ->middleware(['auth', 'verified', 'admin', 'throttle:admin'])
    ->group(function (): void {
        Route::get('/', fn () => Inertia::render('admin/dashboard'))->name('admin.dashboard');
    });
