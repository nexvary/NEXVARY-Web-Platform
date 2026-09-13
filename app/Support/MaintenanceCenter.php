<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Throwable;

final class MaintenanceCenter
{
    /**
     * Return a non-sensitive health snapshot for the control panel.
     *
     * @return array<string, mixed>
     */
    public function status(): array
    {
        $checks = [
            'database' => $this->databaseCheck(),
            'cache' => $this->cacheCheck(),
            'storage' => [
                'ok' => is_writable(storage_path()) && is_writable(storage_path('framework')),
                'label' => 'Storage writable',
            ],
            'build' => [
                'ok' => is_file(public_path('build/manifest.json')),
                'label' => 'Frontend build manifest',
            ],
        ];

        return [
            'healthy' => collect($checks)->every(fn (array $check): bool => (bool) ($check['ok'] ?? false)),
            'checks' => $checks,
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'environment' => app()->environment(),
            'maintenance_mode' => app()->isDownForMaintenance(),
            'checked_at' => now()->toIso8601String(),
        ];
    }

    /**
     * Clear framework/runtime caches without invoking a shell process.
     *
     * @return array<string, mixed>
     */
    public function clearCaches(): array
    {
        $commands = [
            'cache:clear',
            'config:clear',
            'route:clear',
            'view:clear',
            'event:clear',
        ];

        $this->runArtisan($commands);

        return [
            'message' => 'Application caches cleared successfully.',
            'completed_at' => now()->toIso8601String(),
        ];
    }

    /**
     * Rebuild only cache layers that are safe with this application's closure routes.
     * Route caching is intentionally excluded.
     *
     * @return array<string, mixed>
     */
    public function optimize(): array
    {
        $this->runArtisan([
            'config:clear',
            'view:clear',
            'config:cache',
            'view:cache',
        ]);

        return [
            'message' => 'Configuration and compiled views optimized successfully.',
            'completed_at' => now()->toIso8601String(),
        ];
    }

    /**
     * Verify critical runtime files and directories without exposing secrets.
     *
     * @return array<string, mixed>
     */
    public function verifyFiles(): array
    {
        $required = [
            'bootstrap/app.php' => base_path('bootstrap/app.php'),
            'routes/web.php' => base_path('routes/web.php'),
            'routes/final-home.php' => base_path('routes/final-home.php'),
            'resources/views/public/home.blade.php' => resource_path('views/public/home.blade.php'),
            'public/build/manifest.json' => public_path('build/manifest.json'),
        ];

        $files = [];
        foreach ($required as $label => $path) {
            $exists = is_file($path) && is_readable($path);
            $files[$label] = [
                'ok' => $exists,
                'size' => $exists ? filesize($path) : null,
            ];
        }

        $healthy = collect($files)->every(fn (array $file): bool => (bool) $file['ok']);

        return [
            'healthy' => $healthy,
            'files' => $files,
            'checked_at' => now()->toIso8601String(),
        ];
    }

    /**
     * @param list<string> $commands
     */
    private function runArtisan(array $commands): void
    {
        foreach ($commands as $command) {
            try {
                $exitCode = Artisan::call($command);
            } catch (Throwable $exception) {
                throw new RuntimeException("Maintenance action failed while running {$command}.", 0, $exception);
            }

            if ($exitCode !== 0) {
                throw new RuntimeException("Maintenance action {$command} returned exit code {$exitCode}.");
            }
        }
    }

    /** @return array{ok: bool, label: string} */
    private function databaseCheck(): array
    {
        try {
            DB::select('select 1');

            return ['ok' => true, 'label' => 'Database connection'];
        } catch (Throwable) {
            return ['ok' => false, 'label' => 'Database connection'];
        }
    }

    /** @return array{ok: bool, label: string} */
    private function cacheCheck(): array
    {
        $key = 'nexvary:maintenance:health:'.bin2hex(random_bytes(6));

        try {
            Cache::put($key, 'ok', 30);
            $ok = Cache::get($key) === 'ok';
            Cache::forget($key);

            return ['ok' => $ok, 'label' => 'Cache read/write'];
        } catch (Throwable) {
            return ['ok' => false, 'label' => 'Cache read/write'];
        }
    }
}
