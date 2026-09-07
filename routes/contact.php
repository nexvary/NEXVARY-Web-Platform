<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/contact', fn () => Inertia::render('contact'))->name('contact');
