<?php

namespace App\Http\Controllers;

use App\Models\Konsumen;
use App\Models\Makanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreKonsumenRequest;
use App\Http\Requests\UpdateKonsumenRequest;
use Illuminate\Validation\ValidationException;

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

    /**
     * Tampilkan halaman keranjang
     */
    public function viewCart()
    {
        return view('konsumen.cart');
    }

    /**
     * Dashboard konsumen, tampilkan 6 produk terbaru
     */
    public function dashboard()
    {
        $makanan = Makanan::latest()->take(6)->get(); // ambil 6 produk terbaru
        return view('konsumen.dashboard', compact('makanan'));
    }

    /**
     * Tambah produk ke keranjang
     */
    public function addToCart(Request $request)
    {
        try {
            Log::info('Request data:', $request->all());

            $validated = $request->validate([
                'makanan_id' => 'required|exists:makanan,id',
                'quantity' => 'required|integer|min:1',
            ]);

            $cart = session()->get('cart', []);
            $id = $validated['makanan_id'];
            $quantity = $validated['quantity'];

            Log::info('Current cart:', ['cart' => $cart]);

            $makanan = Makanan::findOrFail($id);

            if (isset($cart[$id])) {
                $cart[$id]['quantity'] += $quantity;
            } else {
                $cart[$id] = [
                    'nama' => $makanan->nama_makanan,
                    'harga' => $makanan->harga_makanan,
                    'quantity' => $quantity,
                ];
            }

            session(['cart' => $cart]);
            Log::info('Updated cart:', ['cart' => $cart]);

            $totalQuantity = collect($cart)->sum('quantity');
            $totalPrice = collect($cart)->sum(function ($item) {
                return $item['harga'] * $item['quantity'];
            });

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang!',
                'jmlbarangdibeli' => $totalQuantity,
                'formatted_total' => 'Rp ' . number_format($totalPrice, 0, ',', '.')
            ]);
        } catch (ValidationException $e) {
            Log::error('Validation error:', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error in addToCart:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan ke keranjang'
            ], 500);
        }
    }
}