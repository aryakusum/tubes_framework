<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Makanan extends Model
{
    use HasFactory;
    protected $table = 'makanan';
    protected $fillable = ['id','nama_makanan', 'deskripsi_makanan', 'harga_makanan', 'stok_makanan', 'gambar'];

    
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->harga_makanan, 0, ',', '.');
    }
}
