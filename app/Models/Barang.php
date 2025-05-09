<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// tambahan
use Illuminate\Support\Facades\DB;

class Barang extends Model
{
   use HasFactory;

    protected $table = 'barang'; // Nama tabel eksplisit

    protected $guarded = [];

    public static function getKodeBarang()
    {
        // query kode perusahaan
        $sql = "SELECT IFNULL(MAX(kode_barang), 'AB000') as kode_barang 
                FROM barang ";
        $kodebarang = DB::select($sql);

        // cacah hasilnya
        foreach ($kodebarang as $kdbrg) {
            $kd = $kdbrg->kode_barang;
        }
        // Mengambil substring tiga digit akhir dari string PR-000
        $noawal = substr($kd,-3);
        $noakhir = $noawal+1; //menambahkan 1, hasilnya adalah integer cth 1
        $noakhir = 'AB'.str_pad($noakhir,3,"0",STR_PAD_LEFT); //menyambung dengan string PR-001
        return $noakhir;

    }

    // Dengan mutator ini, setiap kali data harga_barang dikirim ke database, koma akan otomatis dihapus.
    public function setHargaBarangAttribute($value)
    {
        // Hapus koma (,) dari nilai sebelum menyimpannya ke database
        $this->attributes['harga_barang'] = str_replace('.', '', $value);
    }

    // Relasi dengan tabel relasi many to many nya
    public function penjualanBarang()
    {
        return $this->hasMany(PenjualanBarang::class, 'barang_id');
    }

}
