<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanMakanan extends Model
{
    use HasFactory;

    protected $table = 'penjualan_makanan';

    protected $fillable = [
        'penjualan_id',
        'makanan_id',
        'harga_beli',
        'harga_jual',
        'jml',
        'tgl'
    ];

    protected $casts = [
        'tgl' => 'date',
        'harga_beli' => 'integer',
        'harga_jual' => 'integer',
        'jml' => 'integer'
    ];

    public $timestamps = true;

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }

    public function makanan()
    {
        return $this->belongsTo(Makanan::class, 'makanan_id');
    }
}
