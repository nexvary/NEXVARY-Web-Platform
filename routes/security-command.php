<?php

use App\Services\Security\SecurityCommandCenter;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::prefix(config('nexvary.admin_prefix'))
    ->middleware(['auth', 'verified', 'admin', 'throttle:admin', 'audit.admin'])
    ->group(function (): void {
        Route::get('/security-command', function (SecurityCommandCenter $commandCenter) {
            return Inertia::render('admin/security-command', $commandCenter->snapshot());
        })->name('admin.security-command');
    });
