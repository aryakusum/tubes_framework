<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensi'; // Pastikan sama dengan nama tabel di migration
    protected $fillable = [
        'id_pegawai', 'nama', 'tanggal', 'jam_masuk', 'jam_keluar', 'status', 'keterangan', 'mulai_bekerja'
    ];
}
