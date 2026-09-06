<?php

declare(strict_types=1);

namespace App\Providers;

use App\Support\ReleaseUpdater;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use RuntimeException;

final class UpdateServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Route::prefix(config('nexvary.admin_prefix'))
            ->middleware(['web', 'auth', 'verified', 'admin', 'throttle:admin', 'audit.admin'])
            ->group(function (): void {
                Route::get('/updates', function (Request $request, ReleaseUpdater $updater) {
                    abort_unless(in_array($request->user()?->role, ['admin', 'owner'], true), 403);

                    return Inertia::render('admin/updates', [
                        'status' => $updater->status(),
                        'checked' => session('update_checked'),
                        'result' => session('update_result'),
                        'error' => session('update_error'),
                    ]);
                })->name('admin.updates');

                Route::post('/updates/check', function (Request $request, ReleaseUpdater $updater): RedirectResponse {
                    abort_unless(in_array($request->user()?->role, ['admin', 'owner'], true), 403);
                    try {
                        return back()->with('update_checked', $updater->check());
                    } catch (RuntimeException $exception) {
                        return back()->with('update_error', $exception->getMessage());
                    }
                })->name('admin.updates.check');

                Route::post('/updates/install', function (Request $request, ReleaseUpdater $updater): RedirectResponse {
                    abort_unless($request->user()?->role === 'owner', 403);
                    $request->validate([
                        'version' => ['required', 'string', 'max:40'],
                        'download_url' => ['required', 'url:https', 'max:2048'],
                        'sha256' => ['required', 'regex:/^[a-f0-9]{64}$/i'],
                    ]);

                    try {
                        $fresh = $updater->check();
                        abort_unless(
                            hash_equals((string) $fresh['available_version'], (string) $request->string('version'))
                            && hash_equals((string) $fresh['download_url'], (string) $request->string('download_url'))
                            && hash_equals((string) $fresh['sha256'], strtolower((string) $request->string('sha256'))),
                            409,
                            'Update metadata changed. Please check for updates again.'
                        );

                        return back()->with('update_result', $updater->install($fresh));
                    } catch (RuntimeException $exception) {
                        return back()->with('update_error', $exception->getMessage());
                    }
                })->middleware('password.confirm')->name('admin.updates.install');
            });
    }
}
