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
        Schema::create('email_settings', function (Blueprint $table) {
            $table->id();
            $table->string('driver')->default('smtp');
            $table->string('host')->nullable();
            $table->string('port')->nullable();
            $table->string('username')->nullable();
            $table->text('password_encrypted')->nullable();
            $table->string('encryption')->nullable();
            $table->string('from_email')->nullable();
            $table->string('from_name')->nullable();
            $table->timestamps();
        });

        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('type')->unique(); // welcome, quote, invoice, service_expiry
            $table->string('subject');
            $table->text('html_body');
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });

        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->default('Mioly');
            $table->string('logo_path')->nullable();
            $table->string('favicon_path')->nullable();
            $table->string('default_currency')->default('TRY');
            $table->decimal('default_vat_rate', 5, 2)->default(20.00);
            $table->decimal('withholding_rate', 5, 2)->default(0.00);

            // Financial
            $table->string('bank_name')->nullable();
            $table->string('iban')->nullable();
            $table->text('bank_account_info')->nullable();
            $table->string('invoice_prefix')->default('INV');
            $table->integer('invoice_start_number')->default(1000);
            $table->string('quote_prefix')->default('QTE');
            $table->integer('quote_start_number')->default(1000);

            // System
            $table->string('timezone')->default('Europe/Istanbul');
            $table->string('locale')->default('tr');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_settings');
        Schema::dropIfExists('email_templates');
        Schema::dropIfExists('system_settings');
    }
};
