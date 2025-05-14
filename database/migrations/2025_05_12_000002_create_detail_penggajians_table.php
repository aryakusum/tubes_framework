<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('detail_penggajians')) {
            Schema::create('detail_penggajians', function (Blueprint $table) {
                $table->id();
                $table->foreignId('penggajian_id')->constrained('penggajians')->onDelete('cascade');
                $table->foreignId('pegawai_id')->constrained('pegawais')->onDelete('cascade');
                $table->integer('total_hadir');
                $table->decimal('gaji_pokok', 12, 2);
                $table->decimal('tunjangan', 12, 2);
                $table->decimal('potongan', 12, 2);
                $table->decimal('total_gaji', 12, 2);
                $table->text('keterangan')->nullable();
                $table->timestamps();

                // Add a unique constraint to prevent duplicate entries for the same employee in the same payroll
                $table->unique(['penggajian_id', 'pegawai_id'], 'unique_pegawai_penggajian');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_penggajians');
    }
};
