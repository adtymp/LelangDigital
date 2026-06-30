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
        Schema::create('subsubproyeks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('subproyek_id')->constrained('subproyeks')->onDelete('cascade');
            $table->string('nama_halaman');
            $table->string('file_pdf');
            $table->string('file_xls');
            $table->string('total_halaman');
            $table->integer('harga_perlembar');
            $table->integer('kualitas');
            $table->boolean('is_delete')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subsubproyeks');
    }
};
