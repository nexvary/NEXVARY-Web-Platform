<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        RateLimiter::for('admin', function (Request $request): array {
            $identity = (string) ($request->user()?->id ?? $request->ip());

            return [
                Limit::perMinute(30)->by('admin-minute:'.$identity),
                Limit::perHour(300)->by('admin-hour:'.$identity),
            ];
        });

        RateLimiter::for('locale', fn (Request $request): Limit => Limit::perMinute(20)->by($request->ip()));
        RateLimiter::for('public-health', fn (Request $request): Limit => Limit::perMinute(20)->by($request->ip()));
    }
}
