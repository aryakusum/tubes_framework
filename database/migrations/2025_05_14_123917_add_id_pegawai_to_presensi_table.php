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
        Schema::table('presensi', function (Blueprint $table) {
            $table->string('id_pegawai', 10)->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::table('presensi', function (Blueprint $table) {
          //  $table->dropColumn('id_pegawai');
       // });
    }
};
