<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// untuk tambahan db
use Illuminate\Support\Facades\DB;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan'; // Nama tabel eksplisit

    protected $fillable = [
        'pembeli_id',
        'no_faktur',
        'status',
        'tgl',
        'tagihan'
    ];

    protected $casts = [
        'tgl' => 'datetime',
        'tagihan' => 'decimal:2'
    ];

    public $timestamps = true;

    public static function getKodeFaktur()
    {
        // query kode perusahaan
        $sql = "SELECT IFNULL(MAX(no_faktur), 'F-0000000') as no_faktur 
                FROM penjualan ";
        $kodefaktur = DB::select($sql);

        // cacah hasilnya
        foreach ($kodefaktur as $kdpmbl) {
            $kd = $kdpmbl->no_faktur;
        }
        // Mengambil substring tiga digit akhir dari string PR-000
        $noawal = substr($kd, -7);
        $noakhir = $noawal + 1; //menambahkan 1, hasilnya adalah integer cth 1
        $noakhir = 'F-' . str_pad($noakhir, 7, "0", STR_PAD_LEFT); //menyambung dengan string P-00001
        return $noakhir;
    }

    // relasi ke tabel pembeli
    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'pembeli_id');
    }

    // relasi ke tabel penjualan makanan
    public function penjualanMakanan()
    {
        return $this->hasMany(PenjualanMakanan::class, 'penjualan_id');
    }

    public function konsumen()
    {
        return $this->belongsTo(User::class, 'id_konsumen');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'penjualan_id');
    }
}
