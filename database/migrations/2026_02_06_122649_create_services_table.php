<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('provider_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['hosting', 'domain', 'ssl', 'email', 'other']);
            $table->string('name');
            $table->string('identifier_code', 20);
            $table->enum('cycle', ['monthly', 'quarterly', 'yearly', 'biennial', 'custom']);
            $table->enum('payment_type', ['installment', 'upfront'])->default('installment');
            $table->enum('status', ['active', 'suspended', 'cancelled', 'expired'])->default('active');
            $table->enum('currency', ['TRY', 'USD', 'EUR', 'GBP'])->default('TRY');
            $table->decimal('price', 10, 2);
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();

            // Indexes
            $table->index(['customer_id', 'status']);
            $table->index(['provider_id', 'type']);
            $table->index('end_date');
            $table->unique('identifier_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
