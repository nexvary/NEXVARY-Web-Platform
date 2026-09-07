<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rule;

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

        Route::post('/'.trim((string) config('nexvary.admin_prefix'), '/').'/users', function (Request $request): RedirectResponse {
            abort_unless($request->user()?->role === 'owner', 403);

            $validated = $request->validate([
                'name' => ['required', 'string', 'max:120'],
                'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
                'password' => ['required', 'string', 'min:12', 'confirmed'],
                'role' => ['required', Rule::in(['viewer', 'editor', 'security', 'admin'])],
            ]);

            $isAdmin = in_array($validated['role'], ['security', 'admin'], true);

            User::query()->create([
                'name' => trim((string) $validated['name']),
                'email' => strtolower(trim((string) $validated['email'])),
                'password' => Hash::make((string) $validated['password']),
                'email_verified_at' => now(),
                'is_admin' => $isAdmin,
                'role' => $validated['role'],
            ]);

            return back()->with('status', 'Administrative account created successfully.');
        })->middleware(['web', 'auth', 'verified', 'admin', 'throttle:admin', 'audit.admin'])->name('admin.users.create');
    }
}
