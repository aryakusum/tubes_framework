<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';

    protected $fillable = [
        'pembeli_id',
        'no_faktur',
        'status',
        'tgl',
        'tagihan', // meskipun ini dihitung otomatis, tetap diisi saat create jika diperlukan
    ];

    protected $casts = [
        'tgl' => 'datetime',
        'tagihan' => 'decimal:2',
    ];

    public $timestamps = true;

    /**
     * Menghasilkan kode faktur otomatis
     */
    public static function getKodeFaktur()
    {
        $sql = "SELECT IFNULL(MAX(no_faktur), 'F-0000000') as no_faktur FROM penjualan";
        $kodefaktur = DB::select($sql);

        foreach ($kodefaktur as $kdpmbl) {
            $kd = $kdpmbl->no_faktur;
        }

        $noawal = substr($kd, -7);
        $noakhir = (int)$noawal + 1;
        return 'F-' . str_pad($noakhir, 7, "0", STR_PAD_LEFT);
    }

    /**
     * Relasi ke tabel pembeli
     */
    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'pembeli_id');
    }

    /**
     * Relasi ke tabel penjualan makanan
     */
    public function penjualanMakanan()
    {
        return $this->hasMany(PenjualanMakanan::class, 'penjualan_id');
    }

    /**
     * Relasi ke user (jika ada field id_konsumen)
     */
    public function konsumen()
    {
        return $this->belongsTo(User::class, 'id_konsumen');
    }

    /**
     * Relasi ke tabel pembayaran
     */
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'penjualan_id');
    }

    /**
     * Menghitung tagihan dari penjualan makanan (otomatis, tidak tergantung kolom `tagihan`)
     */
    public function getTagihanAttribute()
    {
        return $this->penjualanMakanan->sum(function ($item) {
            return $item->harga_jual * $item->jml;
        });
    }
}
