<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('room_id')->constrained('rooms');
            $table->foreignId('tenant_id')->constrained('tenants');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('deposit_amount', 12, 2)->default(0);
            $table->decimal('monthly_rent', 12, 2);
            $table->enum('status', ['draft', 'active', 'expired', 'terminated'])->default('draft');
            $table->text('terms')->nullable();
            $table->timestamps();
        });

        Schema::create('invoices', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('contract_id')->constrained('contracts');
            $table->string('invoice_no', 50)->unique();
            $table->string('billing_month', 7);
            $table->date('due_date');
            $table->decimal('rent', 12, 2);
            $table->integer('electricity_previous');
            $table->integer('electricity_current');
            $table->decimal('electricity_price', 12, 2);
            $table->integer('water_previous');
            $table->integer('water_current');
            $table->decimal('water_price', 12, 2);
            $table->decimal('service_fee', 12, 2)->default(0);
            $table->integer('electricity_usage');
            $table->integer('water_usage');
            $table->decimal('total_amount', 12, 2);
            $table->enum('status', ['unpaid', 'paid', 'overdue'])->default('unpaid');
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices');
            $table->foreignId('tenant_id')->constrained('tenants');
            $table->string('proof_image_url');
            $table->decimal('amount', 12, 2);
            $table->enum('status', ['pending', 'paid', 'rejected'])->default('pending');
            $table->foreignId('confirmed_by')->nullable()->constrained('users');
            $table->dateTime('confirmed_at')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('contracts');
    }
};
