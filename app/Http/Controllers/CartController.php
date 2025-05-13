<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function viewCart()
    {
        $cart = session('cart', []);
        return view('cart', compact('cart'));
    }

   public function increaseQuantity($id)
{
    // Ambil keranjang dari session
    $cart = session('cart', []);
    
    // Jika produk ada di keranjang, tambah quantity
    if(isset($cart[$id])) {
        $cart[$id]['quantity']++;
    }

    // Simpan kembali keranjang ke session
    session(['cart' => $cart]);

    // Redirect kembali ke halaman keranjang
    return redirect()->route('cart.view');
}

   public function decreaseQuantity($id)
{
    // Ambil keranjang dari session
    $cart = session('cart', []);
    
    // Jika produk ada di keranjang
    if(isset($cart[$id])) {
        // Kurangi quantity jika lebih dari 1
        if($cart[$id]['quantity'] > 1) {
            $cart[$id]['quantity']--;
        } else {
            // Hapus produk jika quantity mencapai 0
            unset($cart[$id]);
        }
    }

    // Simpan kembali keranjang ke session
    session(['cart' => $cart]);

    // Redirect kembali ke halaman keranjang
    return redirect()->route('cart.view');
}

    public function checkout()
    {
        $cart = session('cart', []);
        return view('checkout', compact('cart'));
    }
}

