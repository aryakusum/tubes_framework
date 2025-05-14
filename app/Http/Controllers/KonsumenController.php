<?php

namespace App\Http\Controllers;

use App\Models\Konsumen;
use App\Models\Makanan;
use Illuminate\Http\Request;
use App\Http\Requests\StoreKonsumenRequest;
use App\Http\Requests\UpdateKonsumenRequest;

class KonsumenController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(StoreKonsumenRequest $request)
    {
        //
    }

    public function show(Konsumen $konsumen)
    {
        //
    }

    public function edit(Konsumen $konsumen)
    {
        //
    }

    public function update(UpdateKonsumenRequest $request, Konsumen $konsumen)
    {
        //
    }

    public function destroy(Konsumen $konsumen)
    {
        //
    }

    public function viewCart()
    {
        return view('konsumen.cart');
    }

    public function dashboard()
    {
        $makanan = Makanan::latest()->take(6)->get(); // Ambil 6 produk terbaru
        return view('konsumen.dashboard', compact('makanan'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'makanan_id' => 'required|exists:makanan,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);
        $id = $request->makanan_id;

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $request->quantity;
        } else {
            $makanan = Makanan::find($id);
            $cart[$id] = [
                'name' => $makanan->nama_makanan,
                'price' => $makanan->harga_makanan,
                'quantity' => $request->quantity,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Produk ditambahkan ke keranjang!');
    }
}
