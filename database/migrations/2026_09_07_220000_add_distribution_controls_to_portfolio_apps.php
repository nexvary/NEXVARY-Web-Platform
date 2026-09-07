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
            $table->string('distribution_mode', 32)->default('download')->after('downloads');
            $table->boolean('download_enabled')->default(true)->after('distribution_mode');
            $table->string('request_url', 500)->nullable()->after('download_enabled');
            $table->string('availability_note', 500)->nullable()->after('request_url');
            $table->index(['is_published', 'distribution_mode']);
        });
    }

    public function down(): void
    {
        Schema::table('portfolio_apps', function (Blueprint $table): void {
            $table->dropIndex(['is_published', 'distribution_mode']);
            $table->dropColumn(['distribution_mode', 'download_enabled', 'request_url', 'availability_note']);
        });
    }
};
