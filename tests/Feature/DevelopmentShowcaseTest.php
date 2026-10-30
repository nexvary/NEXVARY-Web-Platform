<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

final class DevelopmentShowcaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_projects_are_localized_crawlable_and_never_downloadable(): void
    {
        $this->withoutVite();
        foreach (['en', 'ar'] as $locale) {
            $catalog = $this->get('/our-work?lang='.$locale)->assertOk();
            foreach (config('project-showcase') as $slug => $project) {
                $catalog->assertSee($project['name']);
                $this->get('/our-work/'.$slug.'?lang='.$locale)->assertOk()
                    ->assertSee($locale === 'ar' ? 'قيد التطوير' : 'In Development')
                    ->assertDontSee('/download', false)
                    ->assertDontSee('github.com/nexvary/NEXVARY-', false)
                    ->assertDontSee('softwareVersion', false);
                $this->get('/our-work/'.$slug.'/download')->assertNotFound();
                $this->get('/sitemap.xml')->assertSee('/our-work/'.$slug, false);
            }
        }
        $this->get('/')->assertDontSee('NEXVARY FoodGuard');
    }

    public function test_old_database_download_setting_cannot_override_showcase_policy(): void
    {
        $this->withoutVite();
        DB::table('portfolio_apps')->insert([
            'name' => 'Stale Recorder Entry', 'slug' => 'nexvary-recorder-studio',
            'platform' => 'Android', 'summary' => 'Old row',
            'is_published' => true, 'distribution_mode' => 'download',
            'download_enabled' => true, 'apk_url' => 'https://example.com/old.apk',
            'created_at' => now(), 'updated_at' => now(),
        ]);
        $this->get('/our-work')->assertOk()->assertDontSee('Stale Recorder Entry');
        $this->get('/our-work/nexvary-recorder-studio')->assertOk()->assertDontSee('old.apk');
        $this->get('/our-work/nexvary-recorder-studio/download')->assertNotFound();
        $this->assertDatabaseHas('portfolio_apps', ['slug' => 'nexvary-recorder-studio', 'downloads' => 0]);
    }
}
