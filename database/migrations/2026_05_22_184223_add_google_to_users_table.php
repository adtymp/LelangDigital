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
        Schema::table('users', function (Blueprint $table) {
            $table->string('google_id')->nullable()->after('id');
            $table->string('avatar')->nullable();
            $table->boolean('is_google_user')->default(false);
            $table->string('password')->nullable()->change();
            $table->string('no_telp')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['google_id', 'avatar', 'is_google_user']);
            $table->string('password')->nullable(false)->change();
            $table->string('no_telp')->nullable(false)->change();
        });
    }
};
