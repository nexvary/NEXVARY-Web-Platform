<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
