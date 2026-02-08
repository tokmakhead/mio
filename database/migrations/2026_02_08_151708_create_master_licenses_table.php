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
        Schema::create('master_licenses', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('client_name');
            $table->string('client_email');
            $table->string('domain')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('type')->default('standard');
            $table->string('status')->default('active'); // active, suspended, cancelled
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_licenses');
    }
};
