<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

final class AdminControlTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    private function owner(): User
    {
        return User::query()->create([
            'name' => 'NEXVARY Owner',
            'email' => 'owner@example.test',
            'password' => 'correct-horse-battery-staple',
            'email_verified_at' => now(),
            'is_admin' => true,
            'role' => 'owner',
        ]);
    }

    public function test_owner_can_promote_user_to_admin_role(): void
    {
        $owner = $this->owner();
        $target = User::query()->create([
            'name' => 'Operator',
            'email' => 'operator@example.test',
            'password' => 'correct-horse-battery-staple',
            'email_verified_at' => now(),
        ]);

        $this->actingAs($owner)
            ->post("/secure-control/users/{$target->id}/role", ['role' => 'admin'])
            ->assertRedirect();

        $target->refresh();
        $this->assertSame('admin', $target->role);
        $this->assertTrue($target->is_admin);
    }

    public function test_last_owner_cannot_demote_their_own_account(): void
    {
        $owner = $this->owner();

        $this->actingAs($owner)
            ->post("/secure-control/users/{$owner->id}/role", ['role' => 'admin'])
            ->assertStatus(422);

        $this->assertSame('owner', $owner->refresh()->role);
    }

    public function test_safescan_admin_can_never_enable_raw_file_storage(): void
    {
        $owner = $this->owner();

        $this->actingAs($owner)
            ->post('/secure-control/safescan', [
                'max_file_mb' => 96,
                'reputation_lookup_enabled' => true,
                'store_raw_files' => true,
            ])
            ->assertRedirect();

        $settings = DB::table('safescan_settings')->first();
        $this->assertSame(96, (int) $settings->max_file_mb);
        $this->assertSame(1, (int) $settings->reputation_lookup_enabled);
        $this->assertSame(0, (int) $settings->store_raw_files);
        $this->assertSame('zero-storage', $settings->privacy_mode);
    }

    public function test_owner_can_update_site_identity_and_seo_settings(): void
    {
        $owner = $this->owner();

        $this->actingAs($owner)
            ->post('/secure-control/settings', [
                'website_url' => 'https://nexvary.com/',
                'contact_email' => 'info@nexvary.com',
                'facebook' => 'https://www.facebook.com/share/14p9krEn5ij/',
                'youtube' => 'https://www.youtube.com/@NexvaryInc',
                'x' => 'https://x.com/Nexvary',
                'seo_title' => 'NEXVARY Security',
                'seo_description' => 'Security technology for cybersecurity, privacy, counter-surveillance and digital forensics.',
                'index_public_pages' => true,
            ])
            ->assertRedirect();

        $this->assertSame('NEXVARY Security', DB::table('site_settings')->where('key', 'seo.default_title')->value('value'));
        $this->assertSame('1', DB::table('site_settings')->where('key', 'seo.index_public_pages')->value('value'));
    }

    public function test_session_revoke_endpoint_is_scoped_to_the_authenticated_user(): void
    {
        $owner = $this->owner();
        $other = User::query()->create([
            'name' => 'Other Admin',
            'email' => 'other@example.test',
            'password' => 'correct-horse-battery-staple',
            'email_verified_at' => now(),
            'is_admin' => true,
            'role' => 'admin',
        ]);

        DB::table('sessions')->insert([
            'id' => 'other-user-session',
            'user_id' => $other->id,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Test Browser',
            'payload' => '',
            'last_activity' => time(),
        ]);

        $this->actingAs($owner)
            ->delete('/secure-control/sessions/other-user-session')
            ->assertRedirect();

        $this->assertDatabaseHas('sessions', [
            'id' => 'other-user-session',
            'user_id' => $other->id,
        ]);
    }

    public function test_passkey_and_totp_management_routes_are_registered(): void
    {
        $routes = collect($this->app['router']->getRoutes())->map(fn ($route): string => $route->uri())->all();

        $this->assertContains('secure-access/passkeys/login/options', $routes);
        $this->assertContains('secure-access/passkeys/login', $routes);
        $this->assertContains('secure-access/user/passkeys/options', $routes);
        $this->assertContains('secure-access/user/passkeys', $routes);
        $this->assertContains('secure-access/user/two-factor-qr-code', $routes);
        $this->assertContains('secure-access/user/confirmed-two-factor-authentication', $routes);
    }
}
