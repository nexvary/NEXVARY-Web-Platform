<?php

declare(strict_types=1);

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

Route::get('/our-work', function () {
    $apps = Schema::hasTable('portfolio_apps')
        ? DB::table('portfolio_apps')->where('is_published', true)->orderByDesc('published_at')->orderByDesc('id')->get([
            'slug', 'name', 'tagline', 'summary', 'platform', 'category', 'version', 'icon_url', 'downloads', 'published_at',
        ])
        : collect();

    return Inertia::render('our-work/index', ['apps' => $apps]);
})->name('our-work.index');

Route::get('/our-work/{slug}', function (string $slug) {
    abort_unless(Schema::hasTable('portfolio_apps'), 404);
    $app = DB::table('portfolio_apps')->where('slug', $slug)->where('is_published', true)->first();
    abort_if($app === null, 404);

    $app->screenshots = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) ($app->screenshots ?? '')) ?: [])));
    $app->features = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) ($app->features ?? '')) ?: [])));

    return Inertia::render('our-work/show', ['app' => $app]);
})->where('slug', '[a-z0-9-]+')->name('our-work.show');

Route::get('/our-work/{slug}/download', function (string $slug): RedirectResponse {
    abort_unless(Schema::hasTable('portfolio_apps'), 404);
    $app = DB::table('portfolio_apps')->where('slug', $slug)->where('is_published', true)->first(['id', 'apk_url']);
    abort_if($app === null || blank($app->apk_url), 404);

    DB::table('portfolio_apps')->where('id', $app->id)->increment('downloads');

    return redirect()->away((string) $app->apk_url);
})->where('slug', '[a-z0-9-]+')->middleware('throttle:60,1')->name('our-work.download');

Route::prefix(config('nexvary.admin_prefix'))
    ->middleware(['auth', 'verified', 'admin', 'throttle:admin', 'audit.admin'])
    ->group(function (): void {
        Route::get('/our-work', function () {
            $apps = Schema::hasTable('portfolio_apps')
                ? DB::table('portfolio_apps')->orderByDesc('id')->get()
                : collect();

            return Inertia::render('admin/our-work', ['apps' => $apps]);
        })->name('admin.our-work');

        Route::post('/our-work', function (Request $request): RedirectResponse {
            abort_unless(in_array($request->user()?->role, ['admin', 'owner'], true), 403);
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:180'],
                'slug' => ['required', 'string', 'max:120', 'regex:/^[a-z0-9-]+$/', Rule::unique('portfolio_apps', 'slug')],
                'tagline' => ['nullable', 'string', 'max:255'],
                'summary' => ['required', 'string', 'max:2000'],
                'description' => ['nullable', 'string', 'max:20000'],
                'platform' => ['required', Rule::in(['Android', 'Windows', 'Web', 'Linux', 'Cross-platform'])],
                'category' => ['nullable', 'string', 'max:100'],
                'version' => ['nullable', 'string', 'max:50'],
                'apk_url' => ['nullable', 'url:https', 'max:500'],
                'apk_size' => ['nullable', 'string', 'max:50'],
                'sha256' => ['nullable', 'regex:/^[a-fA-F0-9]{64}$/'],
                'icon_url' => ['nullable', 'url:https', 'max:500'],
                'screenshots' => ['nullable', 'string', 'max:8000'],
                'features' => ['nullable', 'string', 'max:12000'],
                'changelog' => ['nullable', 'string', 'max:12000'],
                'is_published' => ['required', 'boolean'],
            ]);

            $validated['published_at'] = $validated['is_published'] ? now() : null;
            $validated['created_at'] = now();
            $validated['updated_at'] = now();
            DB::table('portfolio_apps')->insert($validated);

            return back()->with('success', 'Application page created.');
        })->name('admin.our-work.store');

        Route::post('/our-work/{app}', function (Request $request, int $app): RedirectResponse {
            abort_unless(in_array($request->user()?->role, ['admin', 'owner'], true), 403);
            $existing = DB::table('portfolio_apps')->where('id', $app)->first(['id', 'slug', 'published_at']);
            abort_if($existing === null, 404);

            $validated = $request->validate([
                'name' => ['required', 'string', 'max:180'],
                'slug' => ['required', 'string', 'max:120', 'regex:/^[a-z0-9-]+$/', Rule::unique('portfolio_apps', 'slug')->ignore($app)],
                'tagline' => ['nullable', 'string', 'max:255'],
                'summary' => ['required', 'string', 'max:2000'],
                'description' => ['nullable', 'string', 'max:20000'],
                'platform' => ['required', Rule::in(['Android', 'Windows', 'Web', 'Linux', 'Cross-platform'])],
                'category' => ['nullable', 'string', 'max:100'],
                'version' => ['nullable', 'string', 'max:50'],
                'apk_url' => ['nullable', 'url:https', 'max:500'],
                'apk_size' => ['nullable', 'string', 'max:50'],
                'sha256' => ['nullable', 'regex:/^[a-fA-F0-9]{64}$/'],
                'icon_url' => ['nullable', 'url:https', 'max:500'],
                'screenshots' => ['nullable', 'string', 'max:8000'],
                'features' => ['nullable', 'string', 'max:12000'],
                'changelog' => ['nullable', 'string', 'max:12000'],
                'is_published' => ['required', 'boolean'],
            ]);

            $validated['published_at'] = $validated['is_published'] ? ($existing->published_at ?: now()) : null;
            $validated['updated_at'] = now();
            DB::table('portfolio_apps')->where('id', $app)->update($validated);

            return back()->with('success', 'Application page updated.');
        })->name('admin.our-work.update');

        Route::delete('/our-work/{app}', function (Request $request, int $app): RedirectResponse {
            abort_unless($request->user()?->role === 'owner', 403);
            DB::table('portfolio_apps')->where('id', $app)->delete();

            return back()->with('success', 'Application page deleted.');
        })->name('admin.our-work.destroy');
    });
