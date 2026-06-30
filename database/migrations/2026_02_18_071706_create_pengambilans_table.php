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
        Schema::create('pengambilans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignUuid('subsubproyek_id')->constrained('subsubproyeks')->onDelete('cascade');
            $table->integer('dari_halaman');
            $table->integer('sampai_halaman');
            $table->integer('total_halaman');
            $table->integer('harga_perlembar');
            $table->integer('total_harga');
            $table->string('pdf_split')->nullable();
            $table->string('xls_awal')->nullable();
            $table->string('xls_hasil')->nullable();
            $table->enum('status', ['diambil', 'menunggu', 'selesai', 'dibatalkan']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengambilans');
    }
};
