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
        Schema::create('master_releases', function (Blueprint $table) {
            $table->id();
            $table->string('version');
            $table->text('release_notes')->nullable();
            $table->string('file_path');
            $table->boolean('is_critical')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_releases');
    }
};
