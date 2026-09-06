<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

final class SecurityAndSeoTest extends TestCase
{
    public function test_public_security_headers_are_present(): void
    {
        $response = $this->get('/');
        $response->assertOk();
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'DENY');
        $this->assertStringContainsString("object-src 'none'", (string) $response->headers->get('Content-Security-Policy'));
    }

    public function test_private_admin_redirects_unauthenticated_users(): void
    {
        $this->get('/'.trim((string) config('nexvary.admin_prefix'), '/'))->assertRedirect('/login');
    }

    public function test_robots_sitemap_and_security_txt_render(): void
    {
        $this->get('/robots.txt')->assertOk()->assertSee('Sitemap: https://nexvary.com/sitemap.xml', false);
        $this->get('/sitemap.xml')->assertOk()->assertSee('https://nexvary.com/services', false);
        $this->get('/.well-known/security.txt')->assertOk()->assertSee('mailto:info@nexvary.com', false);
    }
}
