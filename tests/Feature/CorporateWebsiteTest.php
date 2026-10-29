<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class CorporateWebsiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_corporate_routes_are_crawlable_in_both_languages(): void
    {
        foreach (['/', '/about', '/our-work', '/artificial-intelligence', '/cybersecurity', '/research', '/contact', '/our-work/foodguard', '/our-work/guardian-studio', '/our-work/nexvary-mr'] as $path) {
            foreach (['en', 'ar'] as $locale) {
                $html = (string) $this->get($path.'?lang='.$locale)->assertOk()->getContent();
                $this->assertStringContainsString('lang="'.$locale.'" dir="'.($locale === 'ar' ? 'rtl' : 'ltr').'"', $html);
                $this->assertSame(1, preg_match_all('/<h1\b/i', $html), $path);
                $this->assertStringContainsString('id="main-content"', $html);
                $this->assertStringNotContainsString('data-page=', $html);
            }
        }
    }

    public function test_project_publication_has_status_dates_and_no_unapproved_source_or_download_links(): void
    {
        $html = (string) $this->get('/our-work?lang=en')->assertOk()->getContent();
        foreach (config('corporate.projects') as $project) {
            $this->assertStringContainsString($project['name'], $html);
            $this->assertStringContainsString($project['date'], $html);
            $this->get('/our-work/'.$project['slug'])->assertOk()->assertSee('Validation boundaries');
        }
        $this->assertStringContainsString('In Development', $html);
        $this->assertStringNotContainsString('github.com/nexvary/NEXVARY-FoodGuard', $html);
        $this->assertDoesNotMatchRegularExpression('/href="[^"\s]+\.(apk|exe|aab|zip)/i', $html);
    }

    public function test_company_home_does_not_present_simulated_telemetry_as_corporate_activity(): void
    {
        $this->get('/')->assertOk()->assertSee('Nexvary Inc')->assertDontSee('ACTIVE SIGNALS')->assertDontSee('PROTECTED')->assertDontSee('DEMONSTRATION DATA');
    }

    public function test_new_pages_are_listed_in_sitemap(): void
    {
        $response = $this->get('/sitemap.xml')->assertOk();
        foreach (['artificial-intelligence', 'cybersecurity', 'research', 'our-work/foodguard', 'our-work/guardian-studio', 'our-work/nexvary-mr'] as $path) {
            $response->assertSee('https://nexvary.com/'.$path, false);
        }
    }

    public function test_unsupported_locale_resets_to_english_after_arabic(): void
    {
        $this->get('/?lang=ar')->assertOk();
        $this->get('/about?lang=tr')->assertOk()->assertSee('<html lang="en" dir="ltr">', false);
    }
}
