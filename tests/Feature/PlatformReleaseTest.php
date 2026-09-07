<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Http\Middleware\EnsureAdmin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
        foreach (['/', '/services', '/our-work', '/apps', '/safescan', '/about', '/contact'] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_public_pages_ship_crawlable_server_rendered_seo(): void
    {
        foreach (['/', '/services', '/our-work', '/apps', '/safescan', '/about', '/contact'] as $path) {
            $response = $this->get($path)->assertOk();
            $html = (string) $response->getContent();

            $this->assertSame(1, preg_match_all('/<h1\b/i', $html), "{$path} must have exactly one raw-HTML H1");
            $this->assertMatchesRegularExpression('/<title>[^<]{15,65}<\/title>/i', $html, "{$path} needs a descriptive raw-HTML title");
            $this->assertMatchesRegularExpression('/<meta\s+name="description"\s+content="[^"]{50,170}"/i', $html, "{$path} needs a raw-HTML meta description");
            $this->assertMatchesRegularExpression('/<link\s+rel="canonical"\s+href="https:\/\/nexvary\.com\//i', $html, "{$path} needs a canonical URL");
            $this->assertStringContainsString('href="/services"', $html, "{$path} must expose internal links before JavaScript");
            $this->assertStringNotContainsString('data-page=', $html, "{$path} should not rely on the Inertia client shell for public SEO content");
        }
    }

    public function test_navigation_targets_have_registered_routes(): void
    {
        foreach (['home', 'services', 'our-work.index', 'apps', 'safescan', 'about', 'contact'] as $routeName) {
            $this->assertTrue(app('router')->has($routeName), "Missing navigation route: {$routeName}");
        }
    }

    public function test_arabic_locale_sets_rtl_shell(): void
    {
        $this->get('/?lang=ar')
            ->assertOk()
            ->assertSee('dir="rtl"', false)
            ->assertSee('lang="ar"', false)
            ->assertSee('الرئيسية');
    }

    public function test_contact_form_persists_valid_request(): void
    {
        $this->post('/contact', [
            'name' => 'Test Client',
            'email' => 'client@example.test',
            'phone' => '+201000000000',
            'company' => 'Example Co',
            'country' => 'Egypt',
            'reason' => 'cybersecurity',
            'preferred_contact' => 'email',
            'message' => 'We need a security assessment for a controlled test environment.',
            'website' => '',
        ])->assertRedirect();

        $this->assertDatabaseHas('contact_requests', [
            'email' => 'client@example.test',
            'reason' => 'cybersecurity',
            'status' => 'new',
        ]);
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
            ->assertSee('https://nexvary.com/our-work', false)
            ->assertSee('https://nexvary.com/contact', false)
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

    public function test_non_admin_user_is_rejected_by_admin_authorization(): void
    {
        $user = User::query()->create([
            'name' => 'Operator',
            'email' => 'operator@example.test',
            'password' => 'correct-horse-battery-staple',
            'email_verified_at' => now(),
        ]);

        $request = request()->create('/secure-control/', 'GET');
        $request->setUserResolver(fn () => $user);

        try {
            (new EnsureAdmin)->handle($request, fn () => response('allowed'));
            $this->fail('Non-admin request was unexpectedly allowed.');
        } catch (HttpException $exception) {
            $this->assertSame(403, $exception->getStatusCode());
        }
    }

    public function test_admin_user_is_accepted_by_admin_authorization(): void
    {
        $admin = User::query()->create([
            'name' => 'Security Admin',
            'email' => 'admin@example.test',
            'password' => 'correct-horse-battery-staple',
            'email_verified_at' => now(),
            'is_admin' => true,
        ]);

        $request = request()->create('/secure-control/', 'GET');
        $request->setUserResolver(fn () => $admin);
        $response = (new EnsureAdmin)->handle($request, fn () => response('allowed'));

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('allowed', $response->getContent());
    }
}
