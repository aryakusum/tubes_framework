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
        Schema::create('makanan', function (Blueprint $table) {
            $table->string('id')->change();
            $table->string('nama_makanan');
            $table->text('deskripsi_makanan');
            $table->decimal('harga_makanan', 10, 2);
            $table->integer('stok_makanan');
            $table->string('gambar')->nullable(); // Izinkan NULL untuk menghindari error
            $table->timestamps();
        });
        
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('makanan');
    }
};
