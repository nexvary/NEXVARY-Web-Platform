<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('app_center_role', 24)->nullable()->after('role')->index();
        });

        Schema::table('portfolio_apps', function (Blueprint $table): void {
            $table->string('review_status', 24)->default('draft')->after('is_published')->index();
            $table->unsignedBigInteger('created_by')->nullable()->after('review_status');
            $table->unsignedBigInteger('approved_by')->nullable()->after('created_by');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
        });

        Schema::create('app_center_audit_events', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('action', 80)->index();
            $table->string('app_slug', 120)->nullable()->index();
            $table->json('meta')->nullable();
            $table->timestamp('created_at')->useCurrent()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_center_audit_events');
        Schema::table('portfolio_apps', function (Blueprint $table): void {
            $table->dropColumn(['review_status', 'created_by', 'approved_by', 'approved_at']);
        });
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn('app_center_role');
        });
    }
};
