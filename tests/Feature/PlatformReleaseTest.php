<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PlatformReleaseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_public_platform_routes_are_available(): void
    {
        foreach (['/', '/services', '/apps', '/safescan', '/about'] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_arabic_locale_sets_rtl_shell(): void
    {
        $this->get('/?lang=ar')
            ->assertOk()
            ->assertSee('dir="rtl"', false)
            ->assertSee('lang="ar"', false);
    }

    public function test_robots_and_sitemap_only_publish_public_surfaces(): void
    {
        $this->get('/robots.txt')
            ->assertOk()
            ->assertSee('Disallow: /secure-control/', false)
            ->assertSee('Sitemap: https://nexvary.com/sitemap.xml', false);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee('https://nexvary.com/services', false)
            ->assertDontSee('secure-control', false)
            ->assertDontSee('secure-access', false);
    }

    public function test_private_login_surface_is_not_indexable_or_cacheable(): void
    {
        $response = $this->get('/secure-access/login');

        $response
            ->assertOk()
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow, noarchive, nosnippet');

        $cacheControl = (string) $response->headers->get('Cache-Control');

        $this->assertStringContainsString('no-store', $cacheControl);
        $this->assertStringContainsString('private', $cacheControl);
        $this->assertStringContainsString('max-age=0', $cacheControl);
    }

    public function test_csp_uses_nonce_and_disallows_unsafe_inline(): void
    {
        $csp = (string) $this->get('/')->headers->get('Content-Security-Policy');

        $this->assertStringContainsString("script-src 'self' 'nonce-", $csp);
        $this->assertStringContainsString("style-src 'self' 'nonce-", $csp);
        $this->assertStringNotContainsString("'unsafe-inline'", $csp);
        $this->assertStringContainsString("connect-src 'self'", $csp);
    }

    public function test_non_admin_user_cannot_open_admin_dashboard(): void
    {
        $user = User::query()->create([
            'name' => 'Operator',
            'email' => 'operator@example.test',
            'password' => 'correct-horse-battery-staple',
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user)
            ->get('/secure-control/')
            ->assertForbidden();
    }

    public function test_admin_user_can_open_admin_dashboard(): void
    {
        $admin = User::query()->create([
            'name' => 'Security Admin',
            'email' => 'admin@example.test',
            'password' => 'correct-horse-battery-staple',
            'email_verified_at' => now(),
            'is_admin' => true,
        ]);

        $this->actingAs($admin)
            ->get('/secure-control/')
            ->assertOk();
    }
}
