<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai'; // Nama tabel eksplisit

    protected $fillable = [
        'nama_pegawai',
        'jenis_kelamin',
        'jenis_pegawai',
        'jabatan',
        'alamat',
        'no_telp',
        'tgl_masuk',
    ];
    public function user()
{
    return $this->belongsTo(User::class);
}
}
