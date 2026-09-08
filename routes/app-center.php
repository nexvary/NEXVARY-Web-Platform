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

Route::prefix('app-center')
    ->middleware(['auth', 'verified', 'app.center', 'throttle:admin'])
    ->group(function () use ($canApprove): void {
        Route::get('/', function (Request $request) {
            $apps = DB::table('portfolio_apps')->latest('updated_at')->get();
            $audit = DB::table('app_center_audit_events')->latest('created_at')->limit(40)->get();

            return Inertia::render('app-center/dashboard', [
                'apps' => $apps,
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
                'apk_url' => ['nullable', 'url', 'max:500'],
                'apk_size' => ['nullable', 'string', 'max:50'],
                'sha256' => ['nullable', 'regex:/^[a-fA-F0-9]{64}$/'],
                'icon_url' => ['nullable', 'url', 'max:500'],
                'distribution_mode' => ['required', Rule::in(['download', 'request', 'private'])],
                'request_url' => ['nullable', 'url', 'max:500'],
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
    });
