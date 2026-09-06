<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

final class PlatformReleaseTest extends TestCase
{
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
        $this->get('/secure-access/login')
            ->assertOk()
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow, noarchive, nosnippet')
            ->assertHeader('Cache-Control', 'no-store, private, max-age=0');
    }
}
