<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Services\Deployment\ProjectManifest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

final class DeploymentPlatformTest extends TestCase
{
    use RefreshDatabase;

    public function test_project_manifest_accepts_a_valid_public_project(): void
    {
        $validated = app(ProjectManifest::class)->validate([
            'name' => 'SafeScan',
            'slug' => 'safescan',
            'description' => 'Privacy-first browser-side static inspection with explicit health verification.',
            'category' => 'Cybersecurity',
            'status' => 'Available',
            'visibility' => 'public',
            'website' => 'https://nexvary.com/safescan',
            'repository' => 'https://github.com/nexvary/NEXVARY-Web-Platform',
            'branch' => 'main',
            'technologies' => ['Laravel', 'TypeScript'],
            'healthCheck' => 'https://nexvary.com/safescan',
            'deploymentMode' => 'deploy_publish',
            'publishToWebsite' => true,
        ]);

        $this->assertSame('safescan', $validated['slug']);
        $this->assertTrue($validated['publishToWebsite']);
    }

    public function test_project_manifest_rejects_invalid_lifecycle_and_non_github_repository(): void
    {
        $this->expectException(ValidationException::class);

        app(ProjectManifest::class)->validate([
            'name' => 'Unsafe Project',
            'slug' => 'unsafe-project',
            'description' => 'This manifest intentionally contains invalid fields for validation coverage.',
            'category' => 'Security',
            'status' => 'Production Ready',
            'visibility' => 'public',
            'repository' => 'https://example.com/repository',
            'technologies' => ['PHP'],
            'publishToWebsite' => true,
        ]);
    }

    public function test_unsigned_deployment_publish_callback_is_not_accepted(): void
    {
        config()->set('deployments.publish_hmac_secret', 'test-secret');

        $this->postJson('/api/deployments/publish', [
            'manifest' => [],
            'deployment' => [],
        ])->assertUnauthorized();
    }

    public function test_privacy_and_terms_are_public_and_crawlable(): void
    {
        $this->get('/privacy')->assertOk()->assertSee('Privacy');
        $this->get('/terms')->assertOk()->assertSee('Terms');
    }
}
