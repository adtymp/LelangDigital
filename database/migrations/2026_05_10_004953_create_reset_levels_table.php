<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reset_levels', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('status', ['aktif', 'nonaktif'])->default('nonaktif');
            $table->integer('lama_hari')->default(30);
            $table->time('jam_reset')->default('00:00:00');
            $table->timestamp('last_reset_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reset_levels');
    }
};
