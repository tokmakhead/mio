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
        Schema::table('master_announcements', function (Blueprint $table) {
            $table->string('target_type')->default('all')->after('type'); // all, license
            $table->foreignId('master_license_id')->nullable()->after('target_type')->constrained()->nullOnDelete();
            $table->string('display_mode')->default('banner')->after('master_license_id'); // banner, modal, feed
            $table->boolean('is_active')->default(true)->after('display_mode');
            $table->boolean('is_dismissible')->default(true)->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_announcements', function (Blueprint $table) {
            $table->dropForeign(['master_license_id']);
            $table->dropColumn(['target_type', 'master_license_id', 'display_mode', 'is_active', 'is_dismissible']);
        });
    }
};
