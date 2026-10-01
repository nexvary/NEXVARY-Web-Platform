<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class AwsReadinessPublicSurfaceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_verified_products_are_visible_without_empty_portfolio_claim(): void
    {
        $response = $this->get('/our-work')->assertOk();

        $response->assertSee('Audio Shield');
        $response->assertSee('Tower Guard');
        $response->assertSee('SafeScan');
        $response->assertDontSee('No published projects yet');
    }

    public function test_public_company_surface_exposes_cloud_ready_language_and_policy_pages(): void
    {
        $this->get('/')->assertOk()
            ->assertSee('Cloud-ready architecture for scalable deployment')
            ->assertSee('DEMONSTRATION DATA');

        $this->get('/about')->assertOk()
            ->assertSee('technology product company')
            ->assertSee('GitHub');

        $this->get('/privacy')->assertOk()->assertSee('Privacy Policy');
        $this->get('/terms')->assertOk()->assertSee('Terms of Use');
    }
}
