<?php

declare(strict_types=1);

use App\Support\MaintenanceCenter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Throwable;

Route::prefix(config('nexvary.admin_prefix'))
    ->middleware(['auth', 'verified', 'admin', 'throttle:admin', 'audit.admin'])
    ->group(function (): void {
        Route::get('/maintenance', function (Request $request, MaintenanceCenter $maintenance) {
            return Inertia::render('admin/maintenance', [
                'status' => $maintenance->status(),
                'lastAction' => $request->session()->get('maintenance.result'),
                'integrity' => $request->session()->get('maintenance.integrity'),
                'error' => $request->session()->get('maintenance.error'),
            ]);
        })->name('admin.maintenance');

        Route::post('/maintenance/clear', function (Request $request, MaintenanceCenter $maintenance): RedirectResponse {
            abort_unless($request->user()?->role === 'owner', 403);

            try {
                return back()->with('maintenance.result', $maintenance->clearCaches());
            } catch (Throwable $exception) {
                report($exception);

                return back()->with('maintenance.error', 'Cache cleanup failed safely. Review the audit/logs before retrying.');
            }
        })->name('admin.maintenance.clear');

        Route::post('/maintenance/optimize', function (Request $request, MaintenanceCenter $maintenance): RedirectResponse {
            abort_unless($request->user()?->role === 'owner', 403);

            try {
                return back()->with('maintenance.result', $maintenance->optimize());
            } catch (Throwable $exception) {
                report($exception);

                return back()->with('maintenance.error', 'Optimization failed safely. The current site configuration was not replaced manually.');
            }
        })->name('admin.maintenance.optimize');

        Route::post('/maintenance/check', function (Request $request, MaintenanceCenter $maintenance): RedirectResponse {
            abort_unless($request->user()?->role === 'owner', 403);

            return back()->with('maintenance.integrity', $maintenance->verifyFiles());
        })->name('admin.maintenance.check');
    });
