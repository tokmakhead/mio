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
        // 1. Update master_licenses table
        Schema::table('master_licenses', function (Blueprint $table) {
            $table->boolean('is_strict')->default(false)->after('status');
            $table->integer('activation_limit')->default(1)->after('is_strict');
        });

        // 2. Create master_license_instances table
        Schema::create('master_license_instances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('master_license_id');
            $table->string('domain')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('version')->nullable();
            $table->timestamp('last_heard_at')->nullable();
            $table->timestamps();

            $table->foreign('master_license_id')
                ->references('id')
                ->on('master_licenses')
                ->onDelete('cascade');

            $table->index(['master_license_id', 'domain']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_license_instances');

        Schema::table('master_licenses', function (Blueprint $table) {
            $table->dropColumn(['is_strict', 'activation_limit']);
        });
    }
};
