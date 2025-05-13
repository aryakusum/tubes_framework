<?php

namespace App\Http\Controllers;

use App\Models\Konsumen;
use App\Models\Barang;
use App\Models\Makanan;
use Illuminate\Http\Request;
use App\Http\Requests\StoreKonsumenRequest;
use App\Http\Requests\UpdateKonsumenRequest;

class KonsumenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKonsumenRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Konsumen $konsumen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Konsumen $konsumen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKonsumenRequest $request, Konsumen $konsumen)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Konsumen $konsumen)
    {
        //
    }

    /**
     * tambahkan KonsumenController
     */
    public function viewCart()
    {
    return view('konsumen.cart');
    }

    /**
     * tambahkan KonsumenController
     */
    public function dashboard()
    {
    $makanan = Makanan::latest()->take(6)->get(); // ambil 6 produk terbaru
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
        $makanan = \App\Models\Makanan::find($id);
        $cart[$id] = [
            'nama' => $makanan->nama_makanan,
            'harga' => $makanan->harga_makanan,
            'quantity' => $request->quantity,
        ];
    }

    session()->put('cart', $cart);

    return redirect()->back()->with('success', 'Produk ditambahkan ke keranjang!');
}

}
