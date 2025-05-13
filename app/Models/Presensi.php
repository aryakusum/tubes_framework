<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensi'; // Pastikan sama dengan nama tabel di migration
    protected $fillable = [
        'id_pegawai',
        'nama',
        'tanggal',
        'jam_masuk',
        'jam_keluar',
        'status',
        'keterangan',
        'mulai_bekerja'
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id');
    }

    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            Pegawai::class,
            'id', // Foreign key on pegawai table
            'id', // Foreign key on users table
            'id_pegawai', // Local key on presensi table
            'user_id' // Local key on pegawai table
        );
    }
}
