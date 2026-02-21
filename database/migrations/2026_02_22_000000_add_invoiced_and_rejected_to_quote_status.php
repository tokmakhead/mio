<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Using raw SQL for ENUM change to avoid doctrine/dbal dependency or potential Blueprint issues
        DB::statement("ALTER TABLE quotes MODIFY COLUMN status ENUM('draft', 'sent', 'accepted', 'rejected', 'invoiced', 'expired') NOT NULL DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE quotes MODIFY COLUMN status ENUM('draft', 'sent', 'accepted', 'expired') NOT NULL DEFAULT 'draft'");
    }
};
