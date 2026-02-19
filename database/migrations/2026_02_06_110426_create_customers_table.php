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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['individual', 'corporate'])->comment('Müşteri türü');
            $table->string('name')->comment('Müşteri adı / Firma adı');
            $table->string('email')->unique()->nullable()->comment('E-posta');
            $table->string('phone')->nullable()->comment('Telefon');
            $table->string('mobile_phone')->nullable()->comment('Mobil telefon');
            $table->string('website')->nullable()->comment('Website');
            $table->text('address')->nullable()->comment('Adres');
            $table->string('city')->nullable()->comment('Şehir');
            $table->string('district')->nullable()->comment('İlçe');
            $table->string('postal_code')->nullable()->comment('Posta kodu');
            $table->string('country')->default('TR')->comment('Ülke');
            $table->string('tax_office')->nullable()->after('country')->comment('Vergi Dairesi');
            $table->string('tax_or_identity_number')->nullable()->comment('Vergi/TC No');
            $table->json('invoice_address')->nullable()->comment('Fatura adresi (farklıysa)');
            $table->enum('status', ['active', 'inactive'])->default('active')->comment('Durum');
            $table->text('notes')->nullable()->comment('Notlar');
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('status');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
