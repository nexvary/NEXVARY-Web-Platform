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
        Schema::create('content_blocks', function (Blueprint $table): void {
            $table->id();
            $table->string('key', 120);
            $table->string('locale', 8);
            $table->string('title')->nullable();
            $table->text('body')->nullable();
            $table->boolean('is_published')->default(false)->index();
            $table->timestamps();
            $table->unique(['key', 'locale']);
        });

        Schema::create('language_settings', function (Blueprint $table): void {
            $table->id();
            $table->string('locale', 8)->unique();
            $table->string('label', 64);
            $table->boolean('is_rtl')->default(false);
            $table->boolean('is_enabled')->default(true)->index();
            $table->unsignedSmallInteger('completion_percent')->default(0);
            $table->timestamps();
        });

        $now = now();
        DB::table('language_settings')->insert([
            ['locale' => 'en', 'label' => 'English', 'is_rtl' => false, 'is_enabled' => true, 'completion_percent' => 100, 'created_at' => $now, 'updated_at' => $now],
            ['locale' => 'ar', 'label' => 'العربية', 'is_rtl' => true, 'is_enabled' => true, 'completion_percent' => 85, 'created_at' => $now, 'updated_at' => $now],
            ['locale' => 'tr', 'label' => 'Türkçe', 'is_rtl' => false, 'is_enabled' => true, 'completion_percent' => 45, 'created_at' => $now, 'updated_at' => $now],
            ['locale' => 'ru', 'label' => 'Русский', 'is_rtl' => false, 'is_enabled' => true, 'completion_percent' => 35, 'created_at' => $now, 'updated_at' => $now],
            ['locale' => 'de', 'label' => 'Deutsch', 'is_rtl' => false, 'is_enabled' => true, 'completion_percent' => 35, 'created_at' => $now, 'updated_at' => $now],
            ['locale' => 'it', 'label' => 'Italiano', 'is_rtl' => false, 'is_enabled' => true, 'completion_percent' => 30, 'created_at' => $now, 'updated_at' => $now],
            ['locale' => 'es', 'label' => 'Español', 'is_rtl' => false, 'is_enabled' => true, 'completion_percent' => 30, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('language_settings');
        Schema::dropIfExists('content_blocks');
    }
};
