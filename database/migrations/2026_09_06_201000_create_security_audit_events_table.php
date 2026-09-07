<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('security_audit_events', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignId('actor_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('event', 96)->index();
            $table->string('method', 12);
            $table->string('route', 255)->nullable();
            $table->char('ip_hash', 64)->nullable();
            $table->char('user_agent_hash', 64)->nullable();
            $table->json('metadata')->nullable();
            $table->char('previous_hash', 64)->nullable();
            $table->char('event_hash', 64)->unique();
            $table->timestamp('created_at')->useCurrent()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('security_audit_events');
    }
};
