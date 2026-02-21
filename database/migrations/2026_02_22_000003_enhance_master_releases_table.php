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
        Schema::table('master_releases', function (Blueprint $table) {
            $table->json('requirements')->nullable()->after('file_path');
            $table->integer('download_count')->default(0)->after('requirements');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_releases', function (Blueprint $table) {
            $table->dropColumn(['requirements', 'download_count']);
        });
    }
};
