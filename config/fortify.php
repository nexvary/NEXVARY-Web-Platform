<?php

declare(strict_types=1);

use Laravel\Fortify\Features;

return [
    'guard' => 'web',
    'middleware' => ['web'],
    'auth_middleware' => 'auth',
    'passwords' => 'users',
    'username' => 'email',
    'email' => 'email',
    'lowercase_usernames' => true,
    'home' => '/'.trim((string) env('NEXVARY_ADMIN_PREFIX', 'secure-control'), '/'),
    'prefix' => trim((string) env('NEXVARY_AUTH_PREFIX', 'secure-access'), '/'),
    'domain' => null,
    'views' => true,
    'limiters' => [
        'login' => 'login',
        'two-factor' => 'two-factor',
    ],
    'passkeys' => [
        'relying_party_id' => parse_url((string) config('app.url'), PHP_URL_HOST),
        'allowed_origins' => [(string) config('app.url')],
        'user_handle_secret' => (string) config('app.key'),
        'timeout' => 60000,
    ],
    'features' => [
        Features::resetPasswords(),
        Features::emailVerification(),
        Features::updatePasswords(),
        Features::twoFactorAuthentication([
            'confirm' => true,
            'confirmPassword' => true,
        ]),
        Features::passkeys([
            'confirmPassword' => true,
        ]),
    ],
];
