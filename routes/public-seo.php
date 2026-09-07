<?php

declare(strict_types=1);

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

Route::get('/', fn () => view('public.home'))->name('home');
Route::get('/services', fn () => view('public.services'))->name('services');
Route::get('/apps', fn () => view('public.apps'))->name('apps');
Route::get('/safescan', fn () => view('public.safescan'))->name('safescan');
Route::get('/about', fn () => view('public.about'))->name('about');
Route::get('/contact', fn () => view('public.contact'))->name('contact');

Route::post('/contact', function (Request $request): RedirectResponse {
    if (filled($request->input('website'))) {
        return back()->with('contact_success', true);
    }

    $validated = $request->validate([
        'name' => ['required', 'string', 'max:120'],
        'email' => ['required', 'email:rfc', 'max:255'],
        'phone' => ['nullable', 'string', 'max:40'],
        'company' => ['nullable', 'string', 'max:160'],
        'country' => ['nullable', 'string', 'max:100'],
        'reason' => ['required', Rule::in(['general', 'cybersecurity', 'tscm', 'forensics', 'privacy', 'partnership', 'support', 'other'])],
        'preferred_contact' => ['required', Rule::in(['email', 'phone', 'whatsapp'])],
        'message' => ['required', 'string', 'min:10', 'max:5000'],
    ]);

    $key = (string) config('app.key', 'nexvary-contact');
    DB::table('contact_requests')->insert([
        ...$validated,
        'locale' => app()->getLocale(),
        'status' => 'new',
        'ip_hash' => $request->ip() ? hash_hmac('sha256', $request->ip(), $key) : null,
        'user_agent_hash' => $request->userAgent() ? hash_hmac('sha256', $request->userAgent(), $key) : null,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return back()->with('contact_success', true);
})->middleware('throttle:10,1')->name('contact.store');

Route::get('/our-work', function () {
    $apps = Schema::hasTable('portfolio_apps')
        ? DB::table('portfolio_apps')->where('is_published', true)->orderByDesc('published_at')->orderByDesc('id')->get([
            'slug', 'name', 'tagline', 'summary', 'platform', 'category', 'version', 'icon_url', 'downloads', 'published_at',
            'distribution_mode', 'download_enabled', 'availability_note',
        ])
        : collect();

    return view('public.our-work.index', ['apps' => $apps]);
})->name('our-work.index');

Route::get('/our-work/{slug}', function (string $slug) {
    abort_unless(Schema::hasTable('portfolio_apps'), 404);
    $app = DB::table('portfolio_apps')->where('slug', $slug)->where('is_published', true)->first();
    abort_if($app === null, 404);

    $app->screenshots = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) ($app->screenshots ?? '')) ?: [])));
    $app->features = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) ($app->features ?? '')) ?: [])));
    $app->can_download = $app->distribution_mode === 'download' && (bool) $app->download_enabled && filled($app->apk_url);

    return view('public.our-work.show', ['app' => $app]);
})->where('slug', '[a-z0-9-]+')->name('our-work.show');

Route::get('/sitemap.xml', function (): Response {
    $urls = ['/', '/services', '/our-work', '/apps', '/safescan', '/about', '/contact'];

    if (Schema::hasTable('portfolio_apps')) {
        foreach (DB::table('portfolio_apps')->where('is_published', true)->orderBy('id')->pluck('slug') as $slug) {
            $urls[] = '/our-work/'.$slug;
        }
    }

    $items = collect($urls)->unique()->map(
        fn (string $path): string => '<url><loc>'.e('https://nexvary.com'.$path).'</loc></url>'
    )->implode('');
    $xml = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.$items.'</urlset>';

    return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
})->name('sitemap');
