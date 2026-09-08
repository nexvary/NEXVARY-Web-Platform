<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

final class SeoCrawlerReleaseGateTest extends TestCase
{
    use RefreshDatabase;

    private const PUBLIC_PATHS = ['/', '/services', '/our-work', '/apps', '/safescan', '/about', '/contact'];

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_public_pages_pass_raw_html_seo_autopilot_gate(): void
    {
        $titles = [];

        foreach (self::PUBLIC_PATHS as $path) {
            $response = $this->get($path)->assertOk();
            $html = (string) $response->getContent();

            preg_match('/<title>(.*?)<\/title>/is', $html, $titleMatch);
            $title = trim(html_entity_decode(strip_tags($titleMatch[1] ?? '')));
            $this->assertGreaterThanOrEqual(15, mb_strlen($title), "{$path}: title is too short");
            $this->assertLessThanOrEqual(65, mb_strlen($title), "{$path}: title is too long");
            $titles[] = mb_strtolower($title);

            preg_match('/<meta\s+name="description"\s+content="([^"]*)"/i', $html, $descriptionMatch);
            $description = html_entity_decode($descriptionMatch[1] ?? '');
            $this->assertGreaterThanOrEqual(50, mb_strlen($description), "{$path}: meta description is too short");
            $this->assertLessThanOrEqual(170, mb_strlen($description), "{$path}: meta description is too long");

            $this->assertSame(1, preg_match_all('/<h1\b/i', $html), "{$path}: expected exactly one H1");
            $this->assertMatchesRegularExpression('/<link\s+rel="canonical"\s+href="https:\/\/nexvary\.com\//i', $html, "{$path}: canonical missing");
            $this->assertMatchesRegularExpression('/<html[^>]+lang="[a-z]{2}/i', $html, "{$path}: language missing");
            $this->assertStringNotContainsString('noindex', strtolower($html), "{$path}: public page must remain indexable");
            $this->assertGreaterThan(0, preg_match_all('/<a\s+[^>]*href="\//i', $html), "{$path}: internal linking missing");

            preg_match_all('/<img\b[^>]*>/i', $html, $images);
            foreach ($images[0] as $image) {
                $this->assertMatchesRegularExpression('/\salt="[^"]*"/i', $image, "{$path}: image without alt attribute");
            }

            $visible = preg_replace('/<(script|style|noscript|template)\b[^>]*>.*?<\/\1>/is', ' ', $html) ?? $html;
            $visible = trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags($visible))) ?? '');
            $wordCount = $visible === '' ? 0 : count(preg_split('/\s+/u', $visible) ?: []);
            $this->assertGreaterThanOrEqual(120, $wordCount, "{$path}: insufficient crawlable text ({$wordCount} words)");
        }

        $this->assertSame(count($titles), count(array_unique($titles)), 'Public pages must not share duplicate titles.');
    }

    public function test_official_social_links_are_exact_and_not_placeholders(): void
    {
        $html = (string) $this->get('/about')->assertOk()->getContent();

        foreach ([
            'https://nexvary.com/',
            'https://www.facebook.com/share/14p9krEn5ij/',
            'mailto:info@nexvary.com',
            'https://www.youtube.com/@NexvaryInc',
            'https://x.com/Nexvary',
        ] as $href) {
            $this->assertStringContainsString('href="'.$href.'"', str_replace('\\"', '"', $html), "Missing official channel: {$href}");
        }

        $this->assertDoesNotMatchRegularExpression('/href="(?:#|javascript:|\s*)"/i', $html);
    }

    public function test_language_fallback_never_declares_english_content_as_an_untranslated_language(): void
    {
        $this->get('/services?lang=tr')
            ->assertOk()
            ->assertSee('<html lang="en" dir="ltr">', false)
            ->assertSee('<option value="en" selected>EN</option>', false);

        $this->get('/services?lang=ar')
            ->assertOk()
            ->assertSee('<html lang="ar" dir="rtl">', false)
            ->assertSee('hreflang="ar"', false)
            ->assertSee('https://nexvary.com/services?lang=ar', false);
    }

    public function test_dynamic_project_page_is_crawlable_and_linked_from_sitemap_and_catalog(): void
    {
        DB::table('portfolio_apps')->insert([
            'slug' => 'sentinel-demo',
            'name' => 'NEXVARY Sentinel Demonstration Project',
            'tagline' => 'A controlled security demonstration project.',
            'summary' => 'A published NEXVARY security project used to verify crawlable metadata, structured data, distribution status, internal linking, and sitemap integrity for dynamic portfolio pages.',
            'description' => str_repeat('This project description documents defensive capabilities, controlled release expectations, verification steps, privacy boundaries, and operational context for visitors reviewing the published project. ', 5),
            'platform' => 'Web',
            'category' => 'Cybersecurity',
            'version' => '1.0.0',
            'features' => "Crawlable project metadata\nControlled distribution status\nStructured release information",
            'distribution_mode' => 'showcase',
            'download_enabled' => false,
            'is_published' => true,
            'published_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $html = (string) $this->get('/our-work/sentinel-demo')->assertOk()->getContent();
        $this->assertSame(1, preg_match_all('/<h1\b/i', $html));
        $this->assertStringContainsString('https://nexvary.com/our-work/sentinel-demo', $html);
        $this->assertStringContainsString('SoftwareApplication', $html);
        $this->assertStringContainsString('href="/our-work"', str_replace('\\"', '"', $html));
        $this->assertStringNotContainsString('data-page=', $html);

        $catalog = (string) $this->get('/our-work')->assertOk()->getContent();
        $this->assertStringContainsString('href="http://localhost/our-work/sentinel-demo"', str_replace('\\"', '"', $catalog));

        $sitemap = (string) $this->get('/sitemap.xml')->assertOk()->getContent();
        $this->assertStringContainsString('https://nexvary.com/our-work/sentinel-demo', $sitemap);
    }

    public function test_every_sitemap_public_url_has_a_crawled_internal_link(): void
    {
        $linked = [];
        foreach (self::PUBLIC_PATHS as $path) {
            $html = (string) $this->get($path)->getContent();
            preg_match_all('/<a\s+[^>]*href="(\/[^"#?]*)/i', $html, $matches);
            foreach ($matches[1] ?? [] as $href) {
                $linked[rtrim($href, '/') ?: '/'] = true;
            }
        }

        $sitemap = (string) $this->get('/sitemap.xml')->assertOk()->getContent();
        preg_match_all('/<loc>https:\/\/nexvary\.com([^<]*)<\/loc>/i', $sitemap, $matches);
        foreach ($matches[1] ?? [] as $path) {
            $normalized = rtrim($path, '/') ?: '/';
            if ($normalized === '/') {
                continue;
            }
            $this->assertArrayHasKey($normalized, $linked, "Sitemap orphan candidate: {$normalized}");
        }
    }
}
