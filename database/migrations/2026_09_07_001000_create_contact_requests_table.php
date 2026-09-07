<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_requests', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 120);
            $table->string('email', 255);
            $table->string('phone', 40)->nullable();
            $table->string('company', 160)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('reason', 80);
            $table->string('preferred_contact', 30)->default('email');
            $table->text('message');
            $table->string('locale', 10)->default('en');
            $table->string('status', 20)->default('new')->index();
            $table->string('ip_hash', 64)->nullable();
            $table->string('user_agent_hash', 64)->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['created_at', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_requests');
    }
};
