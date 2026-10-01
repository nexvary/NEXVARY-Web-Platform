<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Services\Deployment\DeploymentPublisher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Tests\TestCase;

final class DeploymentPublisherTest extends TestCase
{
    use RefreshDatabase;

    public function test_healthy_public_deploy_publish_updates_project_and_records_run(): void
    {
        Http::fake([
            'https://demo.nexvary.com/health' => Http::response('', 200),
        ]);

        $result = app(DeploymentPublisher::class)->publishAfterHealth([
            'name' => 'Deployment Demo',
            'slug' => 'deployment-demo',
            'description' => 'A defensive deployment demonstration used to verify health-gated project publication.',
            'category' => 'Cybersecurity',
            'status' => 'Beta',
            'visibility' => 'public',
            'website' => 'https://demo.nexvary.com',
            'repository' => 'https://github.com/nexvary/deployment-demo',
            'branch' => 'main',
            'technologies' => ['Laravel', 'Docker'],
            'healthCheck' => 'https://demo.nexvary.com/health',
            'deploymentMode' => 'deploy_publish',
            'publishToWebsite' => true,
        ], [
            'provider' => 'coolify',
            'environment' => 'production',
            'commit_sha' => '0123456789abcdef0123456789abcdef01234567',
            'remote_deployment_id' => 'deployment-123',
            'duration_ms' => 1200,
        ]);

        $this->assertTrue($result['published']);
        $this->assertDatabaseHas('portfolio_apps', [
            'slug' => 'deployment-demo',
            'lifecycle_status' => 'Beta',
            'is_published' => 1,
            'last_commit_sha' => '0123456789abcdef0123456789abcdef01234567',
        ]);
        $projectId = (int) \Illuminate\Support\Facades\DB::table('portfolio_apps')->where('slug', 'deployment-demo')->value('id');
        $this->assertDatabaseHas('deployment_runs', [
            'portfolio_app_id' => $projectId,
            'status' => 'Healthy',
            'health_status' => 'HTTP 200',
        ]);
    }

    public function test_external_health_url_is_rejected_before_request(): void
    {
        Http::fake();
        $this->expectException(RuntimeException::class);

        app(DeploymentPublisher::class)->publishAfterHealth([
            'name' => 'External Demo',
            'slug' => 'external-demo',
            'description' => 'A manifest used to verify that deployment health checks cannot target arbitrary external hosts.',
            'category' => 'Cybersecurity',
            'status' => 'Beta',
            'visibility' => 'public',
            'website' => 'https://outside.example',
            'technologies' => ['PHP'],
            'healthCheck' => 'https://outside.example/health',
            'deploymentMode' => 'deploy_publish',
            'publishToWebsite' => true,
        ], ['provider' => 'coolify']);
    }
}
