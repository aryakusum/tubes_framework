<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Makanan;


class Makanan extends Model
{
    use HasFactory;
    protected $table = 'makanan';
    protected $fillable = [
        'kode_makanan',
        'nama_makanan',
        'deskripsi_makanan',
        'harga_makanan',
        'stok_makanan',
        'gambar'
    ];


    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->harga_makanan, 0, ',', '.');
    }

    // Relasi dengan tabel relasi many to many nya
    public function penjualanMakanan()
    {
        return $this->hasMany(PenjualanMakanan::class, 'makanan_id');
    }
}