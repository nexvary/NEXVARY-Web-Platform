<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;

Route::get('/apps', function () {
    $apps = Schema::hasTable('portfolio_apps')
        ? DB::table('portfolio_apps')
            ->where('is_published', true)
            ->where('review_status', 'approved')
            ->orderByDesc('published_at')
            ->get(['id', 'slug', 'name', 'tagline', 'summary', 'platform', 'category', 'version', 'apk_url', 'apk_size', 'sha256', 'icon_url', 'downloads', 'distribution_mode', 'download_enabled', 'request_url', 'availability_note', 'published_at'])
        : collect();

    return Inertia::render('apps', ['apps' => $apps]);
})->name('apps.catalog');
