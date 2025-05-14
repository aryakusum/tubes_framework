<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Snap;
use Illuminate\Support\Facades\Log;
use App\Models\Penjualan;
use App\Models\PenjualanMakanan;
use App\Models\Pembayaran;
use App\Models\Pembeli;
use App\Models\Makanan;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function viewCart()
    {
        $cart = session('cart', []);
        return view('konsumen.cart', compact('cart'));
    }

    public function decreaseQuantity($id)
    {
        $cart = session('cart', []);

        if (isset($cart[$id])) {
            if (request()->has('remove')) {
                unset($cart[$id]);
            } else if ($cart[$id]['quantity'] > 1) {
                $cart[$id]['quantity']--;
            } else {
                unset($cart[$id]);
            }
            session(['cart' => $cart]);
        }

        return redirect()->route('cart.view')->with('success', 'Keranjang berhasil diperbarui');
    }

    public function increaseQuantity($id)
    {
        $cart = session('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
            session(['cart' => $cart]);
        }

        return redirect()->route('cart.view')->with('success', 'Keranjang berhasil diperbarui');
    }

    public function checkout()
    {
        try {
            $cart = session('cart', []);

            if (empty($cart)) {
                return redirect()->route('cart.view')->with('error', 'Keranjang kosong.');
            }

            // Konfigurasi Midtrans
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = false;
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $total = 0;
            $items = [];

            foreach ($cart as $id => $item) {
                if (!isset($item['nama'], $item['harga'], $item['quantity'])) {
                    continue;
                }

                $items[] = [
                    'id' => $id,
                    'price' => $item['harga'],
                    'quantity' => $item['quantity'],
                    'name' => $item['nama']
                ];

                $total += $item['harga'] * $item['quantity'];
            }

            if (empty($items)) {
                return redirect()->route('cart.view')->with('error', 'Keranjang tidak valid.');
            }

            $orderId = 'ORDER-' . time();

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $total,
                ],
                'item_details' => $items,
                'customer_details' => [
                    'first_name' => auth()->user()->name ?? 'Konsumen',
                    'email' => auth()->user()->email ?? 'example@example.com',
                    'phone' => auth()->user()->phone ?? '08123456789',
                ],
                'enabled_payments' => [
                    'credit_card',
                    'bca_va',
                    'bni_va',
                    'bri_va',
                    'mandiri_clickpay',
                    'mandiri_va',
                    'permata_va',
                    'other_va',
                    'gopay',
                    'shopeepay',
                    'qris',
                    'bca_klikbca',
                    'bca_klikpay',
                    'cimb_clicks',
                    'danamon_online'
                ]
            ];

            try {
                $snapToken = Snap::getSnapToken($params);
                return view('konsumen.checkout', compact('snapToken', 'cart', 'orderId', 'total'));
            } catch (\Exception $e) {
                Log::error('Midtrans error: ' . $e->getMessage());
                return redirect()->route('cart.view')->with('error', 'Terjadi kesalahan saat memproses pembayaran.');
            }
        } catch (\Exception $e) {
            Log::error('Checkout error: ' . $e->getMessage());
            return redirect()->route('cart.view')->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    public function handlePaymentFinish(Request $request)
    {
        DB::beginTransaction();
        try {
            $orderId = $request->query('order_id');
            $cart = session('cart', []);

            // Get current user
            $user = auth()->user();
            if (!$user) {
                throw new \Exception("User not authenticated");
            }

            // Get or create pembeli record
            $pembeli = Pembeli::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'nama' => $user->name,
                    'email' => $user->email,
                    'no_hp' => $user->phone ?? '08123456789'
                ]
            );

            // Calculate total
            $total = collect($cart)->sum(function ($item) {
                return $item['harga'] * $item['quantity'];
            });

            // Generate no_faktur
            $noFaktur = 'F-' . Str::padLeft(Penjualan::count() + 1, 7, '0');

            // Create penjualan record
            $penjualan = new Penjualan();
            $penjualan->pembeli_id = $pembeli->id;
            $penjualan->no_faktur = $noFaktur;
            $penjualan->status = 'bayar'; // Set to 'bayar' since payment is complete
            $penjualan->tgl = now();
            $penjualan->tagihan = $total;
            $penjualan->save();

            // Create penjualan_makanan records for each item
            foreach ($cart as $id => $item) {
                $makanan = Makanan::findOrFail($id);

                $penjualanMakanan = new PenjualanMakanan();
                $penjualanMakanan->penjualan_id = $penjualan->id;
                $penjualanMakanan->makanan_id = $id;
                $penjualanMakanan->harga_beli = $makanan->harga_beli ?? $item['harga'];
                $penjualanMakanan->harga_jual = $item['harga'];
                $penjualanMakanan->jml = $item['quantity'];
                $penjualanMakanan->tgl = now();
                $penjualanMakanan->save();
            }

            // Create pembayaran record
            $pembayaran = new Pembayaran();
            $pembayaran->penjualan_id = $penjualan->id;
            $pembayaran->tgl_bayar = now();
            $pembayaran->jenis_pembayaran = $request->query('payment_type', 'midtrans');
            $pembayaran->transaction_time = now();
            $pembayaran->gross_amount = $total;
            $pembayaran->order_id = $orderId;
            $pembayaran->payment_type = $request->query('payment_type', 'midtrans');
            $pembayaran->status_code = '200';
            $pembayaran->transaction_id = $request->query('transaction_id');
            $pembayaran->status_message = 'success';
            $pembayaran->save();

            DB::commit();

            // Clear the cart
            session()->forget('cart');

            // Log successful transaction
            Log::info('Payment successful', [
                'order_id' => $orderId,
                'penjualan_id' => $penjualan->id,
                'total' => $total,
                'user_id' => $user->id
            ]);

            return redirect()->route('konsumen.dashboard')->with('success', 'Pembayaran berhasil! Terima kasih atas pesanan Anda.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment completion error: ' . $e->getMessage());
            return redirect()->route('cart.view')->with('error', 'Terjadi kesalahan dalam memproses pembayaran. Silakan hubungi admin.');
        }
    }

    public function handlePaymentError(Request $request)
    {
        Log::error('Payment error: ' . json_encode($request->all()));
        return redirect()->route('cart.view')->with('error', 'Terjadi kesalahan dalam proses pembayaran. Silakan coba lagi.');
    }

    public function handlePaymentCancel(Request $request)
    {
        return redirect()->route('cart.view')->with('info', 'Pembayaran dibatalkan.');
    }
}
