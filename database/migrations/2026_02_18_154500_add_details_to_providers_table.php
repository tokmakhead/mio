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
        Schema::table('providers', function (Blueprint $table) {
            $table->string('tax_office')->nullable()->after('types')->comment('Vergi Dairesi');
            $table->string('tax_number')->nullable()->after('tax_office')->comment('Vergi Numarası');
            $table->text('address')->nullable()->after('tax_number')->comment('Adres');
            $table->string('custom_type')->nullable()->after('types')->comment('Diğer seçildiğinde girilen özel tür');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('providers', function (Blueprint $table) {
            $table->dropColumn(['tax_office', 'tax_number', 'address', 'custom_type']);
        });
    }
};
