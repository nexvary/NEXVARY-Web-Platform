<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

// Loaded last on purpose: authoritative public home route for the final command-center UI.
// Keep this route server-rendered so the release SEO gate and crawlers receive complete HTML.
Route::get('/', fn () => view('public.home'))->name('home');
