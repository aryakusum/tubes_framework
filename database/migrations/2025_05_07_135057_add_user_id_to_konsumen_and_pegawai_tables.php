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
        Schema::table('konsumen', function (Blueprint $table) {
            // Pengecekan apakah kolom user_id sudah ada
            if (!Schema::hasColumn('konsumen', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable();
            }
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('pegawai', function (Blueprint $table) {
            // Pengecekan apakah kolom user_id sudah ada
            if (!Schema::hasColumn('pegawai', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable();
            }
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('konsumen', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        Schema::table('pegawai', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
