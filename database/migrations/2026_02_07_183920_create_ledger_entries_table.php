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
        Schema::create('ledger_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['debit', 'credit'])->comment('debit: borç (fatura), credit: alacak (ödeme)');
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->default('TRY');
            $table->string('ref_type')->nullable(); // Invoice, Payment, etc.
            $table->unsignedBigInteger('ref_id')->nullable();
            $table->timestamp('occurred_at')->useCurrent();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['customer_id', 'occurred_at']);
            $table->index(['ref_type', 'ref_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ledger_entries');
    }
};
