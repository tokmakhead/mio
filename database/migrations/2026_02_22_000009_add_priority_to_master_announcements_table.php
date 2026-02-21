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
            if (!Schema::hasColumn('master_announcements', 'is_priority')) {
                $table->boolean('is_priority')->default(false)->after('is_dismissible'); // High priority broadcast
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_announcements', function (Blueprint $table) {
            $table->dropColumn('is_priority');
        });
    }
};
