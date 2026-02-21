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
            $table->json('features')->nullable()->after('type'); // Feature flags
            $table->timestamp('trial_ends_at')->nullable()->after('expires_at');
            $table->timestamp('last_sync_at')->nullable()->after('trial_ends_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_licenses', function (Blueprint $table) {
            $table->dropColumn(['features', 'trial_ends_at', 'last_sync_at']);
        });
    }
};
