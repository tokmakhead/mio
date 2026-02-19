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
        Schema::table('system_settings', function (Blueprint $table) {
            $table->integer('default_payment_due_days')->default(14)->after('quote_start_number');
            $table->string('currency_position')->default('prefix')->after('default_currency'); // prefix (₺100) or suffix (100₺)
            $table->string('thousand_separator')->default('.')->after('currency_position');
            $table->string('decimal_separator')->default(',')->after('thousand_separator');
            $table->decimal('currency_precision', 1, 0)->default(2)->after('decimal_separator'); // 2 decimals
            $table->string('rounding_rule')->default('none')->after('currency_precision'); // none, up, down, nearest_1, nearest_10
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('system_settings', function (Blueprint $table) {
            $table->dropColumn([
                'default_payment_due_days',
                'currency_position',
                'thousand_separator',
                'decimal_separator',
                'currency_precision',
                'rounding_rule'
            ]);
        });
    }
};
