<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Services\Deployment\ProjectManifest;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

final class ProjectManifestTest extends TestCase
{
    public function test_valid_manifest_is_accepted(): void
    {
        $manifest = app(ProjectManifest::class)->validate([
            'name' => 'Example Security Product',
            'slug' => 'example-security-product',
            'description' => 'A defensive security product used to verify the project manifest validation contract.',
            'category' => 'Cybersecurity',
            'status' => 'Beta',
            'visibility' => 'public',
            'website' => 'https://example.nexvary.com',
            'repository' => 'https://github.com/nexvary/example-security-product',
            'branch' => 'main',
            'technologies' => ['Laravel', 'Docker'],
            'healthCheck' => 'https://example.nexvary.com/health',
            'deploymentMode' => 'deploy_publish',
            'publishToWebsite' => true,
        ]);

        $this->assertSame('example-security-product', $manifest['slug']);
        $this->assertTrue($manifest['publishToWebsite']);
    }

    public function test_manifest_rejects_unapproved_status_and_repository_host(): void
    {
        $this->expectException(ValidationException::class);

        app(ProjectManifest::class)->validate([
            'name' => 'Invalid Product',
            'slug' => 'invalid-product',
            'description' => 'This payload deliberately violates the manifest contract for a validation test.',
            'category' => 'Cybersecurity',
            'status' => 'Production Ready Forever',
            'visibility' => 'public',
            'repository' => 'https://example.com/not-github',
            'technologies' => ['PHP'],
            'publishToWebsite' => true,
        ]);
    }
}
