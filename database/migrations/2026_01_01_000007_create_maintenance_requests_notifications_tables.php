<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('maintenance_requests', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('room_id')->constrained('rooms');
            $table->foreignId('tenant_id')->constrained('tenants');
            $table->string('title', 150);
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'processing', 'done'])->default('pending');
            $table->timestamps();
        });

        Schema::create('notifications', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('title', 150);
            $table->text('message');
            $table->string('type', 50);
            $table->boolean('is_read')->default(false);
            $table->dateTime('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('maintenance_requests');
    }
};
