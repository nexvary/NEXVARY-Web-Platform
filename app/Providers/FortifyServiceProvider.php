<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Laravel\Fortify\Fortify;

final class FortifyServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Fortify::loginView(fn () => Inertia::render('auth/login'));
        Fortify::twoFactorChallengeView(fn () => Inertia::render('auth/two-factor-challenge'));
        Fortify::confirmPasswordView(fn () => Inertia::render('auth/confirm-password'));

        Fortify::authenticateUsing(function (Request $request): ?User {
            $email = Str::lower(trim((string) $request->input('email')));
            $password = (string) $request->input('password');

            $user = User::query()->whereRaw('LOWER(email) = ?', [$email])->first();

            if ($user === null || ! Hash::check($password, (string) $user->password)) {
                throw ValidationException::withMessages([
                    'email' => 'The email address or password is incorrect.',
                ]);
            }

            if (! $user->is_admin) {
                throw ValidationException::withMessages([
                    'email' => 'This account does not have administrative access.',
                ]);
            }

            return $user;
        });

        RateLimiter::for('login', function (Request $request): Limit {
            $key = Str::transliterate(Str::lower((string) $request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($key);
        });

        RateLimiter::for('two-factor', fn (Request $request): Limit => Limit::perMinute(5)->by((string) $request->session()->get('login.id')));
    }
}
