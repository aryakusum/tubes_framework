<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penggajians', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_penggajian')->unique();
            $table->date('tanggal_penggajian')->index();
            $table->date('periode_awal')->index();
            $table->date('periode_akhir')->index();
            $table->decimal('total_gaji', 12, 2);
            $table->text('keterangan')->nullable();
            $table->enum('status', ['draft', 'diproses', 'selesai', 'dibatalkan'])->default('draft')->index();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Add composite index for period range queries
            $table->index(['periode_awal', 'periode_akhir']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penggajians');
    }
};
