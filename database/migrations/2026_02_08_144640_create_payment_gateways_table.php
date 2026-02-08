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
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('provider')->unique(); // stripe, iyzico
            $table->boolean('is_active')->default(false);
            $table->json('config')->nullable(); // Stores API keys, secrets, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_gateways');
    }
};
