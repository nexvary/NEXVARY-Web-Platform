<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use ZipArchive;

final class ReleaseUpdater
{
    public function status(): array
    {
        $manifestUrl = (string) config('updates.manifest_url');

        return [
            'current_version' => $this->installedVersion(),
            'channel' => (string) config('updates.channel'),
            'configured' => $manifestUrl !== '',
            'manifest_url' => $manifestUrl !== '' ? $this->redactUrl($manifestUrl) : null,
            'private_repo_ready' => filled(config('updates.github_token')),
        ];
    }

    public function check(): array
    {
        $manifestUrl = (string) config('updates.manifest_url');
        if ($manifestUrl === '') {
            throw new RuntimeException('Update manifest URL is not configured.');
        }

        $this->assertAllowedHttpsUrl($manifestUrl);
        $request = $this->request();
        if (str_starts_with($manifestUrl, 'https://api.github.com/')) {
            $request = $request->withHeaders([
                'Accept' => 'application/vnd.github.raw+json',
                'X-GitHub-Api-Version' => '2022-11-28',
            ]);
        } else {
            $request = $request->acceptJson();
        }

        $manifest = $request->get($manifestUrl)->throw()->json();
        if (! is_array($manifest)) {
            throw new RuntimeException('Update manifest is invalid.');
        }

        foreach (['version', 'download_url', 'sha256'] as $key) {
            if (! isset($manifest[$key]) || ! is_string($manifest[$key]) || $manifest[$key] === '') {
                throw new RuntimeException("Update manifest is missing {$key}.");
            }
        }

        $this->assertAllowedHttpsUrl($manifest['download_url']);
        if (! preg_match('/^[a-f0-9]{64}$/i', $manifest['sha256'])) {
            throw new RuntimeException('Update manifest contains an invalid SHA-256.');
        }

        $current = $this->installedVersion();

        return [
            'current_version' => $current,
            'available_version' => $manifest['version'],
            'update_available' => version_compare($manifest['version'], $current, '>'),
            'notes' => is_string($manifest['notes'] ?? null) ? $manifest['notes'] : null,
            'published_at' => is_string($manifest['published_at'] ?? null) ? $manifest['published_at'] : null,
            'download_url' => $manifest['download_url'],
            'sha256' => strtolower($manifest['sha256']),
        ];
    }

    public function install(array $manifest): array
    {
        if (! ($manifest['update_available'] ?? false)) {
            throw new RuntimeException('No newer release is available.');
        }

        $updatesDir = storage_path('app/updates');
        $stageDir = $updatesDir.'/stage';
        $archivePath = $updatesDir.'/release.zip';
        File::ensureDirectoryExists($updatesDir);
        File::deleteDirectory($stageDir);
        File::ensureDirectoryExists($stageDir);

        $this->downloadArchive((string) $manifest['download_url'], $archivePath);
        $actualHash = hash_file('sha256', $archivePath);
        if (! is_string($actualHash) || ! hash_equals((string) $manifest['sha256'], $actualHash)) {
            File::delete($archivePath);
            throw new RuntimeException('Update archive checksum verification failed.');
        }

        $this->extractSafely($archivePath, $stageDir);
        $this->assertReleaseMarker($stageDir, (string) $manifest['available_version']);

        $root = base_path();
        $backupDir = $updatesDir.'/backup-'.date('Ymd-His');
        $this->backupOverlayTargets($stageDir, $root, $backupDir);

        Artisan::call('down', ['--retry' => 30]);
        try {
            $this->overlayDirectory($stageDir, $root);
            Artisan::call('migrate', ['--force' => true]);
            Artisan::call('optimize:clear');
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');
        } catch (\Throwable $exception) {
            $this->restoreBackup($backupDir, $root);
            Artisan::call('optimize:clear');
            throw $exception;
        } finally {
            Artisan::call('up');
            File::delete($archivePath);
            File::deleteDirectory($stageDir);
        }

        return [
            'installed_version' => (string) $manifest['available_version'],
            'backup_path' => $backupDir,
        ];
    }

    private function request(): PendingRequest
    {
        $request = Http::timeout((int) config('updates.timeout', 30));
        $token = trim((string) config('updates.github_token'));

        return $token !== '' ? $request->withToken($token) : $request;
    }

    private function installedVersion(): string
    {
        $marker = base_path('NEXVARY-RELEASE.json');
        if (File::exists($marker)) {
            $decoded = json_decode((string) File::get($marker), true);
            if (is_array($decoded) && is_string($decoded['version'] ?? null) && $decoded['version'] !== '') {
                return $decoded['version'];
            }
        }

        return (string) config('updates.current_version');
    }

    private function assertAllowedHttpsUrl(string $url): void
    {
        $parts = parse_url($url);
        if (! is_array($parts) || ($parts['scheme'] ?? null) !== 'https' || empty($parts['host'])) {
            throw new RuntimeException('Update URL must use HTTPS.');
        }

        $host = strtolower((string) $parts['host']);
        $allowed = array_map('strtolower', (array) config('updates.allowed_hosts', []));
        if (! in_array($host, $allowed, true)) {
            throw new RuntimeException('Update host is not allow-listed.');
        }
    }

    private function downloadArchive(string $url, string $destination): void
    {
        $this->assertAllowedHttpsUrl($url);
        $request = $this->request();
        if (str_starts_with($url, 'https://api.github.com/')) {
            $request = $request->withHeaders([
                'Accept' => 'application/octet-stream',
                'X-GitHub-Api-Version' => '2022-11-28',
            ]);
        }

        $body = $request->get($url)->throw()->body();
        $maxBytes = max(1, (int) config('updates.max_archive_mb', 220)) * 1024 * 1024;
        if (strlen($body) > $maxBytes) {
            throw new RuntimeException('Update archive exceeds configured size limit.');
        }
        File::put($destination, $body);
    }

    private function extractSafely(string $archivePath, string $stageDir): void
    {
        $zip = new ZipArchive;
        if ($zip->open($archivePath) !== true) {
            throw new RuntimeException('Unable to open update archive.');
        }

        try {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $name = (string) $zip->getNameIndex($i);
                if ($name === '' || str_contains($name, "\0") || str_starts_with($name, '/') || str_contains($name, '..') || str_contains($name, '\\')) {
                    throw new RuntimeException('Unsafe path detected in update archive.');
                }
            }
            if (! $zip->extractTo($stageDir)) {
                throw new RuntimeException('Unable to extract update archive.');
            }
        } finally {
            $zip->close();
        }
    }

    private function assertReleaseMarker(string $stageDir, string $version): void
    {
        $marker = $stageDir.'/NEXVARY-RELEASE.json';
        if (! File::exists($marker)) {
            throw new RuntimeException('Release marker is missing.');
        }
        $decoded = json_decode((string) File::get($marker), true);
        if (! is_array($decoded) || ($decoded['version'] ?? null) !== $version) {
            throw new RuntimeException('Release marker version mismatch.');
        }
    }

    private function backupOverlayTargets(string $stageDir, string $root, string $backupDir): void
    {
        File::ensureDirectoryExists($backupDir);
        $created = [];
        foreach (File::allFiles($stageDir, true) as $file) {
            $relative = $file->getRelativePathname();
            if ($relative === '.env' || str_starts_with($relative, 'storage/')) {
                continue;
            }
            $target = $root.'/'.$relative;
            if (File::exists($target)) {
                $backup = $backupDir.'/files/'.$relative;
                File::ensureDirectoryExists(dirname($backup));
                File::copy($target, $backup);
            } else {
                $created[] = $relative;
            }
        }
        File::put($backupDir.'/created.json', json_encode($created, JSON_THROW_ON_ERROR));
    }

    private function restoreBackup(string $backupDir, string $root): void
    {
        $filesDir = $backupDir.'/files';
        if (File::isDirectory($filesDir)) {
            foreach (File::allFiles($filesDir, true) as $file) {
                $target = $root.'/'.$file->getRelativePathname();
                File::ensureDirectoryExists(dirname($target));
                File::copy($file->getPathname(), $target);
            }
        }

        $createdManifest = $backupDir.'/created.json';
        if (File::exists($createdManifest)) {
            $created = json_decode((string) File::get($createdManifest), true);
            if (is_array($created)) {
                foreach ($created as $relative) {
                    if (is_string($relative) && $relative !== '') {
                        File::delete($root.'/'.$relative);
                    }
                }
            }
        }
    }

    private function overlayDirectory(string $source, string $destination): void
    {
        foreach (File::allFiles($source, true) as $file) {
            $relative = $file->getRelativePathname();
            if ($relative === '.env' || str_starts_with($relative, 'storage/')) {
                continue;
            }
            $target = $destination.'/'.$relative;
            File::ensureDirectoryExists(dirname($target));
            File::copy($file->getPathname(), $target);
        }
    }

    private function redactUrl(string $url): string
    {
        $parts = parse_url($url);
        if (! is_array($parts) || empty($parts['scheme']) || empty($parts['host'])) {
            return '[configured]';
        }

        return $parts['scheme'].'://'.$parts['host'].($parts['path'] ?? '');
    }
}
