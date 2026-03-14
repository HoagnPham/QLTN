<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('hostel_id')->constrained('hostels');
            $table->string('room_number', 50);
            $table->decimal('price', 12, 2);
            $table->decimal('area', 8, 2);
            $table->enum('status', ['available', 'occupied', 'maintenance', 'inactive'])->default('available');
            $table->json('utilities')->nullable();
            $table->timestamps();
            $table->unique(['hostel_id', 'room_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
