<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan'; // Nama tabel eksplisit

    protected $fillable = [
        'nama_pegawai',
        'jenis_kelamin',
        'jenis_pegawai',
        'jabatan',
        'alamat',
        'no_telp',
        'tgl_masuk',
    ];
    
}