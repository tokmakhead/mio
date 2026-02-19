<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->enum('discount_type', ['fixed', 'percentage'])->default('fixed')->after('currency');
            $table->decimal('discount_rate', 15, 2)->default(0)->after('discount_type');
        });

        Schema::table('quotes', function (Blueprint $table) {
            $table->enum('discount_type', ['fixed', 'percentage'])->default('fixed')->after('currency');
            $table->decimal('discount_rate', 15, 2)->default(0)->after('discount_type');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['discount_type', 'discount_rate']);
        });

        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn(['discount_type', 'discount_rate']);
        });
    }
};
