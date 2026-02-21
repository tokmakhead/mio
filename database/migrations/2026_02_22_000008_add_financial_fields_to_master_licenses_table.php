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
        Schema::table('master_licenses', function (Blueprint $table) {
            if (!Schema::hasColumn('master_licenses', 'price')) {
                $table->decimal('price', 10, 2)->default(0)->after('type');
            }
            if (!Schema::hasColumn('master_licenses', 'currency')) {
                $table->string('currency', 3)->default('USD')->after('price');
            }
            if (!Schema::hasColumn('master_licenses', 'billing_cycle')) {
                $table->string('billing_cycle')->default('one-time')->after('currency'); // one-time, monthly, yearly
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_licenses', function (Blueprint $table) {
            $table->dropColumn(['price', 'currency', 'billing_cycle']);
        });
    }
};
