<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deployment_runs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('portfolio_app_id')->constrained('portfolio_apps')->cascadeOnDelete();
            $table->string('provider', 32)->default('coolify');
            $table->string('deployment_mode', 32)->default('deploy_only');
            $table->string('environment', 48)->default('production');
            $table->string('repository_url', 500)->nullable();
            $table->string('branch', 120)->nullable();
            $table->string('commit_sha', 64)->nullable();
            $table->string('domain', 500)->nullable();
            $table->string('remote_deployment_id', 160)->nullable()->index();
            $table->string('status', 32)->default('Queued')->index();
            $table->string('health_status', 32)->nullable();
            $table->text('log_summary')->nullable();
            $table->unsignedBigInteger('duration_ms')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->index(['portfolio_app_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deployment_runs');
    }
};
