<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Loaded last on purpose: this is the authoritative public home route for the final command-center UI.
Route::get('/', fn () => Inertia::render('home-final'));
