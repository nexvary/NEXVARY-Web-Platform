<?php

declare(strict_types=1);

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

$canApprove = static fn (Request $request): bool => (string) ($request->user()?->role ?? '') === 'owner'
    || (string) ($request->user()?->app_center_role ?? '') === 'admin';

Route::get('/apps/{slug}/update.json', function (string $slug) {
    $app = DB::table('portfolio_apps')
        ->where('slug', $slug)
        ->where('is_published', true)
        ->where('review_status', 'approved')
        ->first(['id', 'slug', 'name', 'platform']);

    abort_if($app === null, 404);

    $release = DB::table('app_releases')
        ->where('portfolio_app_id', $app->id)
        ->where('channel', 'stable')
        ->where('status', 'published')
        ->orderByDesc('published_at')
        ->orderByDesc('id')
        ->first();

    abort_if($release === null, 404);

    return response()->json([
        'app' => $app->slug,
        'name' => $app->name,
        'platform' => $app->platform,
        'channel' => $release->channel,
        'version' => $release->version,
        'download_url' => $release->download_url,
        'sha256' => strtolower($release->sha256),
        'file_size' => $release->file_size,
        'mandatory' => (bool) $release->is_mandatory,
        'notes' => $release->changelog,
        'published_at' => $release->published_at,
    ], 200, [
        'Cache-Control' => 'public, max-age=300',
        'X-Content-Type-Options' => 'nosniff',
    ]);
})->middleware('throttle:public-health')->name('apps.update-manifest');

Route::prefix('app-center')
    ->middleware(['auth', 'verified', 'app.center', 'throttle:admin'])
    ->group(function () use ($canApprove): void {
        Route::get('/', function (Request $request) {
            $apps = DB::table('portfolio_apps')->latest('updated_at')->get();
            $releases = DB::table('app_releases')
                ->join('portfolio_apps', 'portfolio_apps.id', '=', 'app_releases.portfolio_app_id')
                ->latest('app_releases.updated_at')
                ->limit(80)
                ->get([
                    'app_releases.id', 'app_releases.portfolio_app_id', 'app_releases.version', 'app_releases.channel',
                    'app_releases.download_url', 'app_releases.sha256', 'app_releases.file_size', 'app_releases.changelog',
                    'app_releases.is_mandatory', 'app_releases.status', 'app_releases.published_at',
                    'portfolio_apps.slug as app_slug', 'portfolio_apps.name as app_name',
                ]);
            $audit = DB::table('app_center_audit_events')->latest('created_at')->limit(60)->get();

            return Inertia::render('app-center/dashboard', [
                'apps' => $apps,
                'releases' => $releases,
                'audit' => $audit,
                'canApprove' => $canApprove($request),
                'role' => (string) ($request->user()?->role === 'owner' ? 'owner' : $request->user()?->app_center_role),
            ]);
        })->name('app-center.dashboard');

        Route::post('/apps', function (Request $request): RedirectResponse {
            $data = $request->validate([
                'slug' => ['required', 'string', 'max:120', 'regex:/^[a-z0-9-]+$/', 'unique:portfolio_apps,slug'],
                'name' => ['required', 'string', 'max:180'],
                'tagline' => ['nullable', 'string', 'max:255'],
                'summary' => ['required', 'string', 'max:5000'],
                'description' => ['nullable', 'string', 'max:30000'],
                'platform' => ['required', Rule::in(['Android', 'Windows', 'Linux', 'Web', 'Cross-platform'])],
                'category' => ['nullable', 'string', 'max:100'],
                'version' => ['nullable', 'string', 'max:50'],
                'apk_url' => ['nullable', 'url:https', 'max:500'],
                'apk_size' => ['nullable', 'string', 'max:50'],
                'sha256' => ['nullable', 'regex:/^[a-fA-F0-9]{64}$/'],
                'icon_url' => ['nullable', 'url:https', 'max:500'],
                'distribution_mode' => ['required', Rule::in(['download', 'request', 'private'])],
                'request_url' => ['nullable', 'url:https', 'max:500'],
                'availability_note' => ['nullable', 'string', 'max:500'],
            ]);

            $data['created_by'] = $request->user()?->id;
            $data['review_status'] = 'draft';
            $data['is_published'] = false;
            $data['download_enabled'] = $data['distribution_mode'] === 'download';
            $data['created_at'] = now();
            $data['updated_at'] = now();
            DB::table('portfolio_apps')->insert($data);
            DB::table('app_center_audit_events')->insert([
                'user_id' => $request->user()?->id,
                'action' => 'app.created',
                'app_slug' => $data['slug'],
                'meta' => json_encode(['role' => $request->user()?->app_center_role ?? $request->user()?->role]),
                'created_at' => now(),
            ]);

            return back();
        })->name('app-center.apps.store');

        Route::post('/apps/{app}/submit', function (Request $request, int $app): RedirectResponse {
            DB::table('portfolio_apps')->where('id', $app)->update(['review_status' => 'pending', 'is_published' => false, 'updated_at' => now()]);
            $slug = DB::table('portfolio_apps')->where('id', $app)->value('slug');
            DB::table('app_center_audit_events')->insert(['user_id' => $request->user()?->id, 'action' => 'app.submitted', 'app_slug' => $slug, 'created_at' => now()]);

            return back();
        })->name('app-center.apps.submit');

        Route::post('/apps/{app}/approve', function (Request $request, int $app) use ($canApprove): RedirectResponse {
            abort_unless($canApprove($request), 403);
            DB::table('portfolio_apps')->where('id', $app)->update([
                'review_status' => 'approved',
                'is_published' => true,
                'approved_by' => $request->user()?->id,
                'approved_at' => now(),
                'published_at' => now(),
                'updated_at' => now(),
            ]);
            $slug = DB::table('portfolio_apps')->where('id', $app)->value('slug');
            DB::table('app_center_audit_events')->insert(['user_id' => $request->user()?->id, 'action' => 'app.approved_published', 'app_slug' => $slug, 'created_at' => now()]);

            return back();
        })->name('app-center.apps.approve');

        Route::post('/apps/{app}/unpublish', function (Request $request, int $app) use ($canApprove): RedirectResponse {
            abort_unless($canApprove($request), 403);
            DB::table('portfolio_apps')->where('id', $app)->update(['is_published' => false, 'review_status' => 'draft', 'updated_at' => now()]);
            $slug = DB::table('portfolio_apps')->where('id', $app)->value('slug');
            DB::table('app_center_audit_events')->insert(['user_id' => $request->user()?->id, 'action' => 'app.unpublished', 'app_slug' => $slug, 'created_at' => now()]);

            return back();
        })->name('app-center.apps.unpublish');

        Route::post('/releases', function (Request $request): RedirectResponse {
            $data = $request->validate([
                'portfolio_app_id' => ['required', 'integer', 'exists:portfolio_apps,id'],
                'version' => ['required', 'string', 'max:50', 'regex:/^[0-9A-Za-z][0-9A-Za-z._+-]*$/'],
                'channel' => ['required', Rule::in(['stable', 'beta', 'internal'])],
                'download_url' => ['required', 'url:https', 'max:500'],
                'sha256' => ['required', 'regex:/^[a-fA-F0-9]{64}$/'],
                'file_size' => ['nullable', 'string', 'max:50'],
                'changelog' => ['nullable', 'string', 'max:20000'],
                'is_mandatory' => ['required', 'boolean'],
            ]);

            $exists = DB::table('app_releases')
                ->where('portfolio_app_id', $data['portfolio_app_id'])
                ->where('version', $data['version'])
                ->where('channel', $data['channel'])
                ->exists();
            abort_if($exists, 422, 'This release version already exists for the selected channel.');

            $data['sha256'] = strtolower($data['sha256']);
            $data['status'] = 'draft';
            $data['created_by'] = $request->user()?->id;
            $data['created_at'] = now();
            $data['updated_at'] = now();
            DB::table('app_releases')->insert($data);

            $slug = DB::table('portfolio_apps')->where('id', $data['portfolio_app_id'])->value('slug');
            DB::table('app_center_audit_events')->insert([
                'user_id' => $request->user()?->id,
                'action' => 'release.created',
                'app_slug' => $slug,
                'meta' => json_encode(['version' => $data['version'], 'channel' => $data['channel']]),
                'created_at' => now(),
            ]);

            return back();
        })->name('app-center.releases.store');

        Route::post('/releases/{release}/submit', function (Request $request, int $release): RedirectResponse {
            $row = DB::table('app_releases')->where('id', $release)->first(['id', 'portfolio_app_id', 'version', 'status']);
            abort_if($row === null, 404);
            abort_unless($row->status === 'draft', 422);
            DB::table('app_releases')->where('id', $release)->update(['status' => 'pending', 'updated_at' => now()]);
            $slug = DB::table('portfolio_apps')->where('id', $row->portfolio_app_id)->value('slug');
            DB::table('app_center_audit_events')->insert(['user_id' => $request->user()?->id, 'action' => 'release.submitted', 'app_slug' => $slug, 'meta' => json_encode(['version' => $row->version]), 'created_at' => now()]);

            return back();
        })->name('app-center.releases.submit');

        Route::post('/releases/{release}/approve', function (Request $request, int $release) use ($canApprove): RedirectResponse {
            abort_unless($canApprove($request), 403);
            $row = DB::table('app_releases')->where('id', $release)->first(['id', 'portfolio_app_id', 'version', 'channel', 'status', 'download_url', 'sha256', 'file_size', 'changelog']);
            abort_if($row === null, 404);
            abort_unless($row->status === 'pending', 422);

            DB::transaction(function () use ($row, $release, $request): void {
                DB::table('app_releases')
                    ->where('portfolio_app_id', $row->portfolio_app_id)
                    ->where('channel', $row->channel)
                    ->where('status', 'published')
                    ->update(['status' => 'superseded', 'updated_at' => now()]);

                DB::table('app_releases')->where('id', $release)->update([
                    'status' => 'published',
                    'approved_by' => $request->user()?->id,
                    'approved_at' => now(),
                    'published_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('portfolio_apps')->where('id', $row->portfolio_app_id)->update([
                    'version' => $row->version,
                    'apk_url' => $row->download_url,
                    'apk_size' => $row->file_size,
                    'sha256' => strtolower($row->sha256),
                    'changelog' => $row->changelog,
                    'updated_at' => now(),
                ]);
            });

            $slug = DB::table('portfolio_apps')->where('id', $row->portfolio_app_id)->value('slug');
            DB::table('app_center_audit_events')->insert(['user_id' => $request->user()?->id, 'action' => 'release.published', 'app_slug' => $slug, 'meta' => json_encode(['version' => $row->version, 'channel' => $row->channel]), 'created_at' => now()]);

            return back();
        })->name('app-center.releases.approve');
    });
