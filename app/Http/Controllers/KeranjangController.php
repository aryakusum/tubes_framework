<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Makanan; //untuk akses kelas model makanan
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class KeranjangController extends Controller
{
    public function dashboard()
    {
        // Get all food items
        $makanan = Makanan::all();

        // Get cart data from session
        $cart = Session::get('cart', []);
        $total_belanja = 0;
        $jmlbarangdibeli = 0;

        foreach ($cart as $item) {
            $total_belanja += $item['harga'] * $item['quantity'];
            $jmlbarangdibeli += $item['quantity'];
        }

        return view('galeri', [
            'makanan' => $makanan,
            'total_belanja' => $total_belanja,
            'jmlbarangdibeli' => $jmlbarangdibeli
        ]);
    }

    public function addToCart(Request $request)
    {
        $product_id = $request->product_id;
        $quantity = $request->quantity;

        $makanan = Makanan::find($product_id);

        if (!$makanan) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ]);
        }

        $cart = Session::get('cart', []);

        if (isset($cart[$product_id])) {
            $cart[$product_id]['quantity'] += $quantity;
        } else {
            $cart[$product_id] = [
                'id' => $makanan->id,
                'nama' => $makanan->nama_makanan,
                'harga' => $makanan->harga_makanan,
                'quantity' => $quantity,
                'gambar' => $makanan->gambar
            ];
        }

        Session::put('cart', $cart);

        // Calculate new totals
        $total_belanja = 0;
        $jmlbarangdibeli = 0;

        foreach ($cart as $item) {
            $total_belanja += $item['harga'] * $item['quantity'];
            $jmlbarangdibeli += $item['quantity'];
        }

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan ke keranjang',
            'jmlbarangdibeli' => $jmlbarangdibeli,
            'total' => $total_belanja,
            'formatted_total' => 'Rp ' . number_format($total_belanja, 0, ',', '.')
        ]);
    }
}
