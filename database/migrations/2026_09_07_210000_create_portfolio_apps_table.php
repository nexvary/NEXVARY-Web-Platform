<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolio_apps', function (Blueprint $table): void {
            $table->id();
            $table->string('slug', 120)->unique();
            $table->string('name', 180);
            $table->string('tagline', 255)->nullable();
            $table->text('summary');
            $table->longText('description')->nullable();
            $table->string('platform', 60)->default('Android');
            $table->string('category', 100)->nullable();
            $table->string('version', 50)->nullable();
            $table->string('apk_url', 500)->nullable();
            $table->string('apk_size', 50)->nullable();
            $table->string('sha256', 64)->nullable();
            $table->string('icon_url', 500)->nullable();
            $table->text('screenshots')->nullable();
            $table->longText('features')->nullable();
            $table->longText('changelog')->nullable();
            $table->unsignedBigInteger('downloads')->default(0);
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->index(['is_published', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_apps');
    }
};
