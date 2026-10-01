<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('portfolio_apps', function (Blueprint $table): void {
            $table->string('lifecycle_status', 32)->default('In Development')->after('review_status');
            $table->string('visibility', 24)->default('public')->after('lifecycle_status');
            $table->string('website_url', 500)->nullable()->after('availability_note');
            $table->string('repository_url', 500)->nullable()->after('website_url');
            $table->string('repository_branch', 120)->default('main')->after('repository_url');
            $table->json('technologies')->nullable()->after('repository_branch');
            $table->string('health_url', 500)->nullable()->after('technologies');
            $table->string('deploy_provider', 32)->nullable()->after('health_url');
            $table->string('coolify_resource_uuid', 120)->nullable()->after('deploy_provider');
            $table->boolean('publish_to_website')->default(false)->after('coolify_resource_uuid');
            $table->timestamp('last_deployed_at')->nullable()->after('publish_to_website');
            $table->string('last_commit_sha', 64)->nullable()->after('last_deployed_at');
            $table->index(['visibility', 'lifecycle_status']);
        });

        $now = now();
        $projects = [
            [
                'slug' => 'audio-shield',
                'name' => 'Audio Shield',
                'tagline' => 'Privacy-aware audio protection and analysis tooling.',
                'summary' => 'NEXVARY product work focused on privacy-aware audio protection, analysis and operator workflows.',
                'description' => 'Audio Shield is a NEXVARY security product line for privacy-aware audio protection and analysis workflows. Public availability is controlled while the product continues through release validation.',
                'platform' => 'Cross-platform',
                'category' => 'Privacy Technology',
                'distribution_mode' => 'showcase',
                'download_enabled' => false,
                'availability_note' => 'Controlled release; public package distribution is not currently enabled.',
                'lifecycle_status' => 'Private Preview',
                'visibility' => 'public',
                'technologies' => json_encode(['Security Software', 'Audio Analysis', 'Privacy'], JSON_THROW_ON_ERROR),
                'publish_to_website' => true,
                'is_published' => true,
                'review_status' => 'approved',
                'published_at' => $now,
                'updated_at' => $now,
            ],
            [
                'slug' => 'tower-guard',
                'name' => 'Tower Guard',
                'tagline' => 'Cellular anomaly awareness and validation workflows.',
                'summary' => 'NEXVARY cellular-security product work for anomaly awareness, structured review and professional validation guidance.',
                'description' => 'Tower Guard focuses on cellular anomaly awareness and structured validation workflows. It is presented as a controlled product preview and does not claim that software-only signals replace professional validation.',
                'platform' => 'Cross-platform',
                'category' => 'Cybersecurity',
                'distribution_mode' => 'showcase',
                'download_enabled' => false,
                'availability_note' => 'Controlled release; public package distribution is not currently enabled.',
                'lifecycle_status' => 'Private Preview',
                'visibility' => 'public',
                'technologies' => json_encode(['Cellular Security', 'Signal Analysis', 'Security Monitoring'], JSON_THROW_ON_ERROR),
                'publish_to_website' => true,
                'is_published' => true,
                'review_status' => 'approved',
                'published_at' => $now,
                'updated_at' => $now,
            ],
            [
                'slug' => 'safescan',
                'name' => 'SafeScan',
                'tagline' => 'Zero-storage browser-side file inspection.',
                'summary' => 'A privacy-first NEXVARY web application for browser-side static file inspection with an optional reputation workflow.',
                'description' => 'SafeScan demonstrates NEXVARY\'s privacy-first product approach by keeping primary static inspection in the browser and minimizing unnecessary file transfer.',
                'platform' => 'Web',
                'category' => 'Cybersecurity',
                'distribution_mode' => 'showcase',
                'download_enabled' => false,
                'availability_note' => 'Available as a web application on nexvary.com.',
                'lifecycle_status' => 'Available',
                'visibility' => 'public',
                'website_url' => 'https://nexvary.com/safescan',
                'health_url' => 'https://nexvary.com/safescan',
                'technologies' => json_encode(['Web', 'Privacy', 'Zero-storage Inspection'], JSON_THROW_ON_ERROR),
                'publish_to_website' => true,
                'is_published' => true,
                'review_status' => 'approved',
                'published_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($projects as $project) {
            $slug = $project['slug'];
            unset($project['slug']);
            DB::table('portfolio_apps')->updateOrInsert(
                ['slug' => $slug],
                [...$project, 'created_at' => $now],
            );
        }
    }

    public function down(): void
    {
        Schema::table('portfolio_apps', function (Blueprint $table): void {
            $table->dropIndex(['visibility', 'lifecycle_status']);
            $table->dropColumn([
                'lifecycle_status',
                'visibility',
                'website_url',
                'repository_url',
                'repository_branch',
                'technologies',
                'health_url',
                'deploy_provider',
                'coolify_resource_uuid',
                'publish_to_website',
                'last_deployed_at',
                'last_commit_sha',
            ]);
        });
    }
};
