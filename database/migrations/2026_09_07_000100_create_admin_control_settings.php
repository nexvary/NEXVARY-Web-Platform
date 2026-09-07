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
        Schema::table('users', function (Blueprint $table): void {
            $table->string('role', 32)->default('viewer')->index()->after('is_admin');
        });

        DB::table('users')->where('is_admin', true)->update(['role' => 'owner']);

        Schema::create('site_settings', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->boolean('is_secret')->default(false);
            $table->timestamps();
        });

        Schema::create('safescan_settings', function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('max_file_mb')->default(128);
            $table->boolean('reputation_lookup_enabled')->default(false);
            $table->boolean('store_raw_files')->default(false);
            $table->string('privacy_mode', 40)->default('zero-storage');
            $table->timestamps();
        });

        DB::table('safescan_settings')->insert([
            'max_file_mb' => 128,
            'reputation_lookup_enabled' => false,
            'store_raw_files' => false,
            'privacy_mode' => 'zero-storage',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $defaults = [
            'site.url' => 'https://nexvary.com/',
            'contact.email' => 'info@nexvary.com',
            'social.facebook' => 'https://www.facebook.com/share/14p9krEn5ij/',
            'social.youtube' => 'https://www.youtube.com/@NexvaryInc',
            'social.x' => 'https://x.com/Nexvary',
            'seo.default_title' => 'NEXVARY',
            'seo.default_description' => 'Cybersecurity, counter-surveillance, digital forensics and privacy-first security technology.',
            'seo.index_public_pages' => '1',
        ];

        foreach ($defaults as $key => $value) {
            DB::table('site_settings')->insert([
                'key' => $key,
                'value' => $value,
                'is_secret' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('safescan_settings');
        Schema::dropIfExists('site_settings');

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn('role');
        });
    }
};
