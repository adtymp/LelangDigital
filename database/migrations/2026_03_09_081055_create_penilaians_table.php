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
        Schema::create('penilaians', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pengambilan_id')->constrained('pengambilans')->onDelete('cascade');
            $table->json('skor');
            $table->json('catatan');
            $table->decimal('total_skor');
            $table->integer('total_poin');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaians');
    }
};
