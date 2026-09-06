<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn () => Inertia::render('home'))->name('home');

Route::get('/about', fn () => Inertia::render('about'))->name('about');

Route::get('/health', fn () => response()->json(['status' => 'ok']))
    ->middleware('throttle:30,1')
    ->name('health.public');

Route::prefix(config('nexvary.admin_prefix'))
    ->middleware(['auth', 'verified', 'throttle:60,1'])
    ->group(function (): void {
        Route::get('/', fn () => Inertia::render('admin/dashboard'))->name('admin.dashboard');
    });
