<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

final class SecurityIntelligenceTest extends TestCase
{
    public function test_cisa_catalog_is_normalized_and_attributed(): void
    {
        Cache::forget('nexvary.cisa-kev.v1');
        Http::fake(['raw.githubusercontent.com/*' => Http::response([
            'catalogVersion' => '1', 'dateReleased' => '2026-09-26T10:00:00Z', 'count' => 1,
            'vulnerabilities' => [[
                'cveID' => 'CVE-2026-12345', 'dateAdded' => '2026-09-26',
                'vendorProject' => 'Example', 'product' => 'Server',
                'vulnerabilityName' => 'Example flaw', 'knownRansomwareCampaignUse' => 'Unknown',
            ]],
        ])]);

        $this->getJson('/api/security-intelligence')->assertOk()
            ->assertJsonPath('source', 'CISA KEV')
            ->assertJsonPath('catalog_count', 1)
            ->assertJsonPath('entries.0.cve', 'CVE-2026-12345');
    }

    public function test_geographic_feed_does_not_invent_events_without_provider(): void
    {
        $this->getJson('/api/threat-feed')->assertOk()
            ->assertJsonPath('mode', 'unavailable')
            ->assertJsonCount(0, 'events');
    }
}
