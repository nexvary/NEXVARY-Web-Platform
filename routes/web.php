<?php

declare(strict_types=1);

use App\Support\ReleaseUpdater;
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
    $allowIndexing = Schema::hasTable('site_settings') === false || DB::table('site_settings')->where('key', 'seo.index_public_pages')->value('value') !== '0';
    $body = $allowIndexing
        ? "User-agent: *\nDisallow: /{$admin}/\nDisallow: /health\nSitemap: https://nexvary.com/sitemap.xml\n"
        : "User-agent: *\nDisallow: /\n";

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
                ['title' => $validated['title'] ?? null, 'body' => $validated['body'] ?? null, 'is_published' => $validated['is_published'], 'updated_at' => now(), 'created_at' => DB::raw('COALESCE(created_at, CURRENT_TIMESTAMP)')],
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
            $validated = $request->validate(['is_enabled' => ['required', 'boolean'], 'completion_percent' => ['required', 'integer', 'between:0,100']]);
            DB::table('language_settings')->where('locale', $locale)->update(['is_enabled' => $validated['is_enabled'], 'completion_percent' => $validated['completion_percent'], 'updated_at' => now()]);

            return back();
        })->name('admin.languages.update');

        Route::get('/users', function (Request $request) {
            $users = DB::table('users')->orderBy('id')->get(['id', 'name', 'email', 'role', 'is_admin', 'email_verified_at', 'two_factor_confirmed_at', 'created_at'])->map(fn (object $user): array => [
                'id' => (int) $user->id,
                'name' => (string) $user->name,
                'email' => (string) $user->email,
                'role' => (string) $user->role,
                'is_admin' => (bool) $user->is_admin,
                'email_verified' => $user->email_verified_at !== null,
                'mfa_enabled' => $user->two_factor_confirmed_at !== null,
                'created_at' => (string) $user->created_at,
            ]);

            return Inertia::render('admin/users', ['users' => $users, 'currentUserId' => (int) $request->user()->id]);
        })->name('admin.users');

        Route::post('/users/{user}/role', function (Request $request, int $user): RedirectResponse {
            abort_unless($request->user()?->role === 'owner', 403);
            $validated = $request->validate(['role' => ['required', Rule::in(['viewer', 'editor', 'security', 'admin', 'owner'])]]);
            $target = DB::table('users')->where('id', $user)->first(['id', 'role']);
            abort_if($target === null, 404);
            abort_if((int) $target->id === (int) $request->user()->id && $target->role === 'owner' && $validated['role'] !== 'owner', 422, 'The active owner cannot demote their own account.');
            if ($target->role === 'owner' && $validated['role'] !== 'owner') {
                abort_if(DB::table('users')->where('role', 'owner')->count() <= 1, 422, 'At least one owner is required.');
            }
            $isAdmin = in_array($validated['role'], ['security', 'admin', 'owner'], true);
            DB::table('users')->where('id', $user)->update(['role' => $validated['role'], 'is_admin' => $isAdmin, 'updated_at' => now()]);

            return back();
        })->name('admin.users.role');

        Route::get('/sessions', function (Request $request) {
            $sessionId = $request->session()->getId();
            $sessions = DB::table('sessions')->where('user_id', $request->user()->id)->orderByDesc('last_activity')->get(['id', 'ip_address', 'user_agent', 'last_activity'])->map(fn (object $session): array => [
                'id' => (string) $session->id,
                'ip_address' => $session->ip_address ? (string) $session->ip_address : null,
                'user_agent' => $session->user_agent ? (string) $session->user_agent : null,
                'last_activity' => date(DATE_ATOM, (int) $session->last_activity),
                'current' => hash_equals($sessionId, (string) $session->id),
            ]);

            return Inertia::render('admin/sessions', ['sessions' => $sessions]);
        })->name('admin.sessions');

        Route::delete('/sessions/{session}', function (Request $request, string $session): RedirectResponse {
            abort_if(hash_equals($request->session()->getId(), $session), 422, 'The current session cannot be revoked here.');
            DB::table('sessions')->where('id', $session)->where('user_id', $request->user()->id)->delete();

            return back();
        })->name('admin.sessions.destroy');

        Route::delete('/sessions', function (Request $request): RedirectResponse {
            DB::table('sessions')->where('user_id', $request->user()->id)->where('id', '!=', $request->session()->getId())->delete();

            return back();
        })->name('admin.sessions.destroy-others');

        Route::get('/security', function (Request $request) {
            $passkeys = Schema::hasTable('passkeys')
                ? DB::table('passkeys')->where('user_id', $request->user()->id)->latest('created_at')->get(['id', 'name', 'last_used_at', 'created_at'])
                : collect();

            return Inertia::render('admin/security', [
                'mfaEnabled' => filled($request->user()->two_factor_secret),
                'mfaConfirmed' => filled($request->user()->two_factor_confirmed_at),
                'passkeys' => $passkeys,
                'authPrefix' => trim((string) config('fortify.prefix'), '/'),
            ]);
        })->name('admin.security');

        Route::get('/safescan', function () {
            $settings = DB::table('safescan_settings')->first(['max_file_mb', 'reputation_lookup_enabled', 'store_raw_files', 'privacy_mode']);

            return Inertia::render('admin/safescan', ['settings' => $settings]);
        })->name('admin.safescan');

        Route::post('/safescan', function (Request $request): RedirectResponse {
            $validated = $request->validate(['max_file_mb' => ['required', 'integer', 'between:1,512'], 'reputation_lookup_enabled' => ['required', 'boolean']]);
            DB::table('safescan_settings')->updateOrInsert(['id' => 1], ['max_file_mb' => $validated['max_file_mb'], 'reputation_lookup_enabled' => $validated['reputation_lookup_enabled'], 'store_raw_files' => false, 'privacy_mode' => 'zero-storage', 'updated_at' => now(), 'created_at' => DB::raw('COALESCE(created_at, CURRENT_TIMESTAMP)')]);

            return back();
        })->name('admin.safescan.update');

        Route::get('/updates', function (Request $request, ReleaseUpdater $updater) {
            return Inertia::render('admin/updates', [
                'status' => $updater->status(),
                'checked' => $request->session()->get('update.checked'),
                'result' => $request->session()->get('update.result'),
                'error' => $request->session()->get('update.error'),
            ]);
        })->name('admin.updates');

        Route::post('/updates/check', function (Request $request, ReleaseUpdater $updater): RedirectResponse {
            abort_unless($request->user()?->role === 'owner', 403);

            try {
                return back()->with('update.checked', $updater->check());
            } catch (Throwable $exception) {
                report($exception);

                return back()->with('update.error', 'Unable to verify the update source. Check the configured release channel and try again.');
            }
        })->name('admin.updates.check');

        Route::post('/updates/install', function (Request $request, ReleaseUpdater $updater): RedirectResponse {
            abort_unless($request->user()?->role === 'owner', 403);

            try {
                $manifest = $updater->check();
                $result = $updater->install($manifest);

                return back()->with('update.result', $result);
            } catch (Throwable $exception) {
                report($exception);

                return back()->with('update.error', 'Update installation failed safely. The site was returned from maintenance mode; review the logs before retrying.');
            }
        })->middleware('password.confirm')->name('admin.updates.install');

        Route::get('/settings', function () {
            $settings = DB::table('site_settings')->pluck('value', 'key');

            return Inertia::render('admin/settings', ['settings' => $settings]);
        })->name('admin.settings');

        Route::post('/settings', function (Request $request): RedirectResponse {
            abort_unless(in_array($request->user()?->role, ['admin', 'owner'], true), 403);
            $validated = $request->validate([
                'website_url' => ['required', 'url:https', 'max:255'],
                'contact_email' => ['required', 'email', 'max:255'],
                'facebook' => ['required', 'url:https', 'max:255'],
                'youtube' => ['required', 'url:https', 'max:255'],
                'x' => ['required', 'url:https', 'max:255'],
                'seo_title' => ['required', 'string', 'max:70'],
                'seo_description' => ['required', 'string', 'max:180'],
                'index_public_pages' => ['required', 'boolean'],
            ]);
            $pairs = [
                'site.url' => $validated['website_url'],
                'contact.email' => $validated['contact_email'],
                'social.facebook' => $validated['facebook'],
                'social.youtube' => $validated['youtube'],
                'social.x' => $validated['x'],
                'seo.default_title' => $validated['seo_title'],
                'seo.default_description' => $validated['seo_description'],
                'seo.index_public_pages' => $validated['index_public_pages'] ? '1' : '0',
            ];
            foreach ($pairs as $key => $value) {
                DB::table('site_settings')->updateOrInsert(['key' => $key], ['value' => $value, 'is_secret' => false, 'updated_at' => now(), 'created_at' => DB::raw('COALESCE(created_at, CURRENT_TIMESTAMP)')]);
            }

            return back();
        })->name('admin.settings.update');
    });
