<?php

declare(strict_types=1);

use App\Http\Middleware\AdminAudit;
use App\Http\Middleware\AdminPrivacyHeaders;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [SetLocale::class, SecurityHeaders::class]);
        $middleware->alias([
            'admin.audit' => AdminAudit::class,
            'admin.privacy' => AdminPrivacyHeaders::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Central reporting hooks are intentionally isolated from public responses.
    })->create();
