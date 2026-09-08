<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_releases', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('portfolio_app_id')->constrained('portfolio_apps')->cascadeOnDelete();
            $table->string('version', 50);
            $table->string('channel', 32)->default('stable');
            $table->string('download_url', 500);
            $table->string('sha256', 64);
            $table->string('file_size', 50)->nullable();
            $table->longText('changelog')->nullable();
            $table->boolean('is_mandatory')->default(false);
            $table->string('status', 24)->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->unique(['portfolio_app_id', 'version', 'channel']);
            $table->index(['portfolio_app_id', 'status', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_releases');
    }
};
