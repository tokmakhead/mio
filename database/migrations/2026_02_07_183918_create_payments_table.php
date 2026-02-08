<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->default('TRY');
            $table->string('method')->default('cash'); // cash, bank, card, etc.
            $table->timestamp('paid_at')->useCurrent();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['invoice_id', 'customer_id']);
            $table->index('paid_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
