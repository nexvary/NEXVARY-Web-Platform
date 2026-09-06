<?php

declare(strict_types=1);

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
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

Route::get('/.well-known/security.txt', function (): Response {
    $body = implode("\n", [
        'Contact: mailto:info@nexvary.com',
        'Canonical: https://nexvary.com/.well-known/security.txt',
        'Preferred-Languages: en, ar',
        'Policy: https://nexvary.com/about',
        '',
    ]);

    return response($body, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
})->name('security.txt');

Route::get('/sitemap.xml', function (): Response {
    $urls = ['/', '/services', '/apps', '/safescan', '/about'];
    $items = collect($urls)->map(fn (string $path): string => '<url><loc>'.e('https://nexvary.com'.$path).'</loc></url>')->implode('');
    $xml = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.$items.'</urlset>';

    return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
})->name('sitemap');

Route::prefix(config('nexvary.admin_prefix'))
    ->middleware(['auth', 'verified', 'admin', 'throttle:admin', 'audit.admin'])
    ->group(function (): void {
        Route::get('/', function (Request $request) {
            $hasAuditTable = Schema::hasTable('security_audit_events');
            $auditCount = $hasAuditTable ? DB::table('security_audit_events')->count() : 0;
            $recentAudit = $hasAuditTable
                ? DB::table('security_audit_events')->latest('created_at')->limit(8)->get(['id', 'event', 'method', 'route', 'created_at'])->map(fn (object $row): array => [
                    'id' => (string) $row->id,
                    'event' => (string) $row->event,
                    'method' => (string) $row->method,
                    'route' => (string) $row->route,
                    'created_at' => (string) $row->created_at,
                ])
                : collect();

            return Inertia::render('admin/dashboard', [
                'security' => [
                    'mfa_enabled' => filled($request->user()?->two_factor_secret),
                    'email_verified' => $request->user()?->hasVerifiedEmail() ?? false,
                    'audit_events' => $auditCount,
                    'csp_nonce' => true,
                    'private_cache_control' => true,
                    'shared_hosting_mode' => true,
                ],
                'recentAudit' => $recentAudit,
            ]);
        })->name('admin.dashboard');

        Route::get('/audit', function () {
            $events = Schema::hasTable('security_audit_events')
                ? DB::table('security_audit_events')->latest('created_at')->limit(100)->get(['id', 'event', 'method', 'route', 'created_at'])->map(fn (object $row): array => [
                    'id' => (string) $row->id,
                    'event' => (string) $row->event,
                    'method' => (string) $row->method,
                    'route' => (string) $row->route,
                    'created_at' => (string) $row->created_at,
                ])
                : collect();

            return Inertia::render('admin/audit', ['events' => $events]);
        })->name('admin.audit');

        Route::get('/content', function () {
            $blocks = Schema::hasTable('content_blocks')
                ? DB::table('content_blocks')->orderBy('key')->orderBy('locale')->get(['key', 'locale', 'title', 'body', 'is_published'])
                : collect();

            return Inertia::render('admin/content', ['blocks' => $blocks]);
        })->name('admin.content');

        Route::post('/content', function (Request $request): RedirectResponse {
            $validated = $request->validate([
                'key' => ['required', 'string', 'max:120', 'regex:/^[a-z0-9._-]+$/'],
                'locale' => ['required', 'string', Rule::in(config('nexvary.languages', ['en']))],
                'title' => ['nullable', 'string', 'max:255'],
                'body' => ['nullable', 'string', 'max:20000'],
                'is_published' => ['required', 'boolean'],
            ]);

            DB::table('content_blocks')->updateOrInsert(
                ['key' => $validated['key'], 'locale' => $validated['locale']],
                [
                    'title' => $validated['title'] ?? null,
                    'body' => $validated['body'] ?? null,
                    'is_published' => $validated['is_published'],
                    'updated_at' => now(),
                    'created_at' => DB::raw('COALESCE(created_at, CURRENT_TIMESTAMP)'),
                ],
            );

            return back();
        })->name('admin.content.save');

        Route::get('/languages', function () {
            $languages = Schema::hasTable('language_settings')
                ? DB::table('language_settings')->orderBy('id')->get(['locale', 'label', 'is_rtl', 'is_enabled', 'completion_percent'])
                : collect();

            return Inertia::render('admin/languages', ['languages' => $languages]);
        })->name('admin.languages');

        Route::post('/languages/{locale}', function (Request $request, string $locale): RedirectResponse {
            abort_unless(in_array($locale, config('nexvary.languages', ['en']), true), 404);
            $validated = $request->validate([
                'is_enabled' => ['required', 'boolean'],
                'completion_percent' => ['required', 'integer', 'between:0,100'],
            ]);

            DB::table('language_settings')->where('locale', $locale)->update([
                'is_enabled' => $validated['is_enabled'],
                'completion_percent' => $validated['completion_percent'],
                'updated_at' => now(),
            ]);

            return back();
        })->name('admin.languages.update');
    });
