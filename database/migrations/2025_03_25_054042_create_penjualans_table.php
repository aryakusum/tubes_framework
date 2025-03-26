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
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pegawai');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->enum('jenis_Pegawai', ['Pegawai','Kurir']);
            $table->string('jabatan');
            $table->string('alamat');
            $table->string('no_telp');
            $table->date('tgl_masuk');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};
