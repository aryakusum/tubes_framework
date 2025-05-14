<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Makanan;
use App\Models\Penjualan;
use App\Models\PenjualanMakanan;
use App\Models\Pembayaran;
use App\Models\Pembeli;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class KeranjangController extends Controller
{
    public function daftarmakanan()
    {
        // ambil session
        $id_user = Auth::user()->id;

        // dapatkan id_pembeli dari user_id di tabel users sesuai data yang login
        $pembeli = Pembeli::where('user_id', $id_user)
                    ->select(DB::raw('id'))
                    ->first();
        $id_pembeli = $pembeli->id;

        // ambil data makanan
        $makanan = Makanan::all();

        $jmlmakanandibeli = DB::table('penjualan')
                            ->join('penjualan_makanan', 'penjualan.id', '=', 'penjualan_makanan.penjualan_id')
                            ->join('pembeli', 'penjualan.pembeli_id', '=', 'pembeli.id')
                            ->join('pembayaran', 'penjualan.id', '=', 'pembayaran.penjualan_id')
                            ->select(DB::raw('COUNT(DISTINCT makanan_id) as total'))
                            ->where('penjualan.pembeli_id', '=', $id_pembeli) 
                            ->where(function($query) {
                                $query->where('pembayaran.gross_amount', 0)
                                      ->orWhere(function($q) {
                                          $q->where('pembayaran.status_code', '!=', 200)
                                            ->where('pembayaran.jenis_pembayaran', 'pg');
                                      });
                            })
                            ->get();

        $t = DB::table('penjualan')
            ->join('penjualan_makanan', 'penjualan.id', '=', 'penjualan_makanan.penjualan_id')
            ->join('pembayaran', 'penjualan.id', '=', 'pembayaran.penjualan_id')
            ->select(DB::raw('SUM(harga_jual * jml) as total'))
            ->where('penjualan.pembeli_id', '=', $id_pembeli) 
            ->where(function($query) {
                $query->where('pembayaran.gross_amount', 0)
                        ->orWhere(function($q) {
                            $q->where('pembayaran.status_code', '!=', 200)
                            ->where('pembayaran.jenis_pembayaran', 'pg');
                        });
            })
            ->first();

        // kirim ke halaman view
        return view('galeri',
                        [ 
                            'makanan'=>$makanan,
                            'total_belanja' => $t->total ?? 0,
                            'jmlmakanandibeli' => $jmlmakanandibeli[0]->total ?? 0
                        ]
                    ); 
    }

    // halaman tambah keranjang
    public function tambahKeranjang(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        try {
            $request->validate([
                'product_id' => 'required|exists:makanan,id',
                'quantity' => 'required|integer|min:1'
            ]);
    
            $id_user = Auth::user()->id;

            // dapatkan id_pembeli dari user_id di tabel users sesuai data yang login
            $pembeli = Pembeli::where('user_id', $id_user)
                            ->select(DB::raw('id'))
                            ->first();
            $id_pembeli = $pembeli->id;

            try{
                $product = Makanan::find($request->product_id);
                if (!$product) {
                    return response()->json(['success' => false, 'message' => 'makanan tidak ditemukan!']);
                }
                $harga = $product->harga_makanan;
                $jumlah = (int) $request->quantity;
                $makanan_id = $request->product_id;

                // Cek apakah ada penjualan dengan gross_amount = 0
                $penjualanExist = DB::table('penjualan')
                ->join('penjualan_makanan', 'penjualan.id', '=', 'penjualan_makanan.penjualan_id')
                ->join('pembayaran', 'penjualan.id', '=', 'pembayaran.penjualan_id')
                ->where('penjualan.pembeli_id', $id_pembeli)
                ->where(function($query) {
                    $query->where('pembayaran.gross_amount', 0)
                          ->orWhere(function($q) {
                              $q->where('pembayaran.status_code', '!=', 200)
                                ->where('pembayaran.jenis_pembayaran', 'pg');
                          });
                })
                ->select('penjualan.id')
                ->first();

                if (!$penjualanExist) {
                    // Buat penjualan baru jika tidak ada
                    $penjualan = Penjualan::create([
                        'no_faktur'   => Penjualan::getKodeFaktur(),
                        'tgl'         => now(),
                        'pembeli_id'  => $id_pembeli,
                        'tagihan'     => 0,
                        'status'      => 'pesan',
                    ]);

                    // Buat pembayaran baru
                    $pembayaran = Pembayaran::create([
                        'penjualan_id'      => $penjualan->id,
                        'tgl_bayar'         => now(),
                        'jenis_pembayaran'  => 'pg',
                        'gross_amount'      => 0,
                    ]);
                } else {
                    $penjualan = Penjualan::find($penjualanExist->id);
                }

                // Tambahkan makanan ke penjualan_makanan
                PenjualanMakanan::create([
                    'penjualan_id' => $penjualan->id,
                    'makanan_id' => $makanan_id,
                    'jml' => $jumlah,
                    'harga_beli' => $harga,
                    'harga_jual' => $harga * 1.2,
                    'tgl' => date('Y-m-d')
                ]);

                // Update total tagihan
                $tagihan = DB::table('penjualan')
                ->join('penjualan_makanan', 'penjualan.id', '=', 'penjualan_makanan.penjualan_id')
                ->join('pembayaran', 'penjualan.id', '=', 'pembayaran.penjualan_id')
                ->select(DB::raw('SUM(harga_jual * jml) as total'))
                ->where('penjualan.pembeli_id', '=', $id_pembeli) 
                ->where(function($query) {
                    $query->where('pembayaran.gross_amount', 0)
                          ->orWhere(function($q) {
                              $q->where('pembayaran.status_code', '!=', 200)
                                ->where('pembayaran.jenis_pembayaran', 'pg');
                          });
                })
                ->first();
                $penjualan->tagihan = $tagihan->total;
                $penjualan->save();

                // update stok makanan
                Makanan::where('id', $makanan_id)->decrement('stok', $jumlah);

                // hitung total makanan
                $jmlmakanandibeli = DB::table('penjualan')
                            ->join('penjualan_makanan', 'penjualan.id', '=', 'penjualan_makanan.penjualan_id')
                            ->join('pembeli', 'penjualan.pembeli_id', '=', 'pembeli.id')
                            ->join('pembayaran', 'penjualan.id', '=', 'pembayaran.penjualan_id')
                            ->select(DB::raw('COUNT(DISTINCT makanan_id) as total'))
                            ->where('penjualan.pembeli_id', '=', $id_pembeli) 
                            ->where(function($query) {
                                $query->where('pembayaran.gross_amount', 0)
                                      ->orWhere(function($q) {
                                          $q->where('pembayaran.status_code', '!=', 200)
                                            ->where('pembayaran.jenis_pembayaran', 'pg');
                                      });
                            })
                            ->get();

                return response()->json([
                    'success' => true, 
                    'message' => 'Transaksi berhasil ditambahkan!', 
                    'total' => $penjualan->tagihan, 
                    'jmlmakanandibeli' => $jmlmakanandibeli[0]->total ?? 0
                ]);

            } catch(\Exception $e) {
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => $e->getMessage()
            ]);
        }
    }

    // ... [Rest of the methods from pembayaran branch remain unchanged]
}