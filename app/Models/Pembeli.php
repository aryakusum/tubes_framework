<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// tambahan
use Illuminate\Support\Facades\DB;

class Pembeli extends Model
{
    use HasFactory;

    protected $table = 'pembeli'; // Nama tabel eksplisit

    protected $guarded = []; //semua kolom boleh di isi

    public static function getKodePembeli()
    {
        // query kode perusahaan
        $sql = "SELECT IFNULL(MAX(kode_pembeli), 'P-00000') as kode_pembeli 
                FROM pembeli ";
        $kodepembeli = DB::select($sql);

        // cacah hasilnya
        foreach ($kodepembeli as $kdpmbl) {
            $kd = $kdpmbl->kode_pembeli;
        }
        // Mengambil substring tiga digit akhir dari string PR-000
        $noawal = substr($kd,-5);
        $noakhir = $noawal+1; //menambahkan 1, hasilnya adalah integer cth 1
        $noakhir = 'P-'.str_pad($noakhir,5,"0",STR_PAD_LEFT); //menyambung dengan string P-00001
        return $noakhir;

    }

    // relasi ke tabel pembeli
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); 
        // pastikan 'user_id' adalah nama kolom foreign key
    }

    // relasi ke tabel penjualan
    public function penjualan()
    {
        return $this->hasMany(Penjualan::class, 'pembeli_id');
    }
}
