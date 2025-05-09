<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Barang; //untuk akses kelas model barang

class KeranjangController extends Controller
{
    public function dashboard()
    {
        // ambil data barang
        $barang = Barang::all();
        // kirim ke halaman view
        return view('galeri',
                        [ 
                            'barang'=>$barang,
                        ]
                    ); 
    }
}
