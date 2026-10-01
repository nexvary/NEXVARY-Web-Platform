<?php

declare(strict_types=1);

namespace App\Services\Deployment;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use RuntimeException;

final class DeploymentPublisher
{
    public function __construct(private readonly ProjectManifest $manifest)
    {
    }

    /**
     * @param array<string, mixed> $rawManifest
     * @param array<string, mixed> $deployment
     * @return array<string, mixed>
     */
    public function publishAfterHealth(array $rawManifest, array $deployment): array
    {
        $manifest = $this->manifest->validate($rawManifest);
        $website = $manifest['website'] ?? null;
        $healthUrl = $manifest['healthCheck'] ?? $website;

        if (! is_string($healthUrl) || $healthUrl === '') {
            throw new RuntimeException('A HTTPS healthCheck or website URL is required before a project can be published.');
        }

        $this->assertAllowedHttpsUrl($healthUrl);
        $response = Http::acceptJson()->timeout(10)->get($healthUrl);
        if ($response->status() !== 200) {
            throw new RuntimeException('Health check did not return HTTP 200.');
        }

        if (is_string($website) && $website !== '') {
            $this->assertAllowedHttpsUrl($website);
        }

        return DB::transaction(function () use ($manifest, $deployment, $healthUrl): array {
            $publish = (bool) $manifest['publishToWebsite']
                && ($manifest['deploymentMode'] ?? 'deploy_only') === 'deploy_publish'
                && $manifest['visibility'] === 'public';

            $now = now();
            $projectData = [
                'name' => $manifest['name'],
                'tagline' => null,
                'summary' => $manifest['description'],
                'description' => $manifest['description'],
                'platform' => 'Cross-platform',
                'category' => $manifest['category'],
                'distribution_mode' => 'showcase',
                'download_enabled' => false,
                'availability_note' => $manifest['status'],
                'lifecycle_status' => $manifest['status'],
                'visibility' => $manifest['visibility'],
                'website_url' => $manifest['website'] ?? null,
                'repository_url' => $manifest['repository'] ?? null,
                'repository_branch' => $manifest['branch'] ?? 'main',
                'technologies' => json_encode($manifest['technologies'], JSON_THROW_ON_ERROR),
                'health_url' => $healthUrl,
                'deploy_provider' => (string) ($deployment['provider'] ?? 'coolify'),
                'publish_to_website' => (bool) $manifest['publishToWebsite'],
                'last_deployed_at' => $now,
                'last_commit_sha' => $deployment['commit_sha'] ?? null,
                'is_published' => $publish,
                'review_status' => $publish ? 'approved' : 'draft',
                'published_at' => $publish ? $now : null,
                'updated_at' => $now,
            ];

            DB::table('portfolio_apps')->updateOrInsert(['slug' => $manifest['slug']], [
                ...$projectData,
                'created_at' => $now,
            ]);

            $project = DB::table('portfolio_apps')->where('slug', $manifest['slug'])->first(['id', 'slug']);
            if ($project === null) {
                throw new RuntimeException('Project metadata could not be persisted.');
            }

            $runId = DB::table('deployment_runs')->insertGetId([
                'portfolio_app_id' => $project->id,
                'provider' => (string) ($deployment['provider'] ?? 'coolify'),
                'deployment_mode' => (string) ($manifest['deploymentMode'] ?? 'deploy_only'),
                'environment' => (string) ($deployment['environment'] ?? 'production'),
                'repository_url' => $manifest['repository'] ?? null,
                'branch' => $manifest['branch'] ?? 'main',
                'commit_sha' => $deployment['commit_sha'] ?? null,
                'domain' => $manifest['website'] ?? null,
                'remote_deployment_id' => $deployment['remote_deployment_id'] ?? null,
                'status' => 'Healthy',
                'health_status' => 'HTTP 200',
                'log_summary' => 'Deployment verified by the publish callback and server-side HTTPS health check.',
                'duration_ms' => $deployment['duration_ms'] ?? null,
                'started_at' => $deployment['started_at'] ?? null,
                'completed_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            return ['project' => $manifest['slug'], 'deployment_run_id' => $runId, 'published' => $publish];
        });
    }

    private function assertAllowedHttpsUrl(string $url): void
    {
        $parts = parse_url($url);
        $host = strtolower((string) ($parts['host'] ?? ''));
        $scheme = strtolower((string) ($parts['scheme'] ?? ''));
        $suffix = strtolower((string) config('deployments.allowed_host_suffix', 'nexvary.com'));

        $allowed = $host === $suffix || str_ends_with($host, '.'.$suffix);
        if ($scheme !== 'https' || ! $allowed) {
            throw new RuntimeException('Project health and website URLs must use HTTPS on the configured NEXVARY domain suffix.');
        }
    }
}
