<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Snap;

class CartController extends Controller
{
    public function viewCart()
    {
        $cart = session('cart', []);
        return view('konsumen.keranjang', compact('cart'));
    }

    public function decreaseQuantity($id)
    {
        $cart = session('cart', []);
        if (isset($cart[$id])) {
            if ($cart[$id]['quantity'] > 1) {
                $cart[$id]['quantity']--;
            } else {
                unset($cart[$id]);
            }
        }
        session(['cart' => $cart]);
        return redirect()->route('cart.view');
    }

    public function increaseQuantity($id)
    {
        $cart = session('cart', []);
        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        }
        session(['cart' => $cart]);
        return redirect()->route('cart.view');
    }

    public function checkout()
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.view')->with('error', 'Keranjang kosong.');
        }

        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $total = 0;
        $items = [];

        foreach ($cart as $id => $item) {
            if (!isset($item['price'], $item['quantity'], $item['name'])) {
                continue;
            }

            $items[] = [
                'id' => $id,
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'name' => $item['name']
            ];

            $total += $item['price'] * $item['quantity'];
        }

        if (empty($items)) {
            return redirect()->route('cart.view')->with('error', 'Keranjang tidak valid.');
        }

        $params = [
            'transaction_details' => [
                'order_id' => uniqid(),
                'gross_amount' => $total,
            ],
            'item_details' => $items,
            'customer_details' => [
                'first_name' => auth()->user()->name ?? 'Konsumen',
                'email' => auth()->user()->email ?? 'example@example.com',
            ]
        ];

        $snapToken = Snap::getSnapToken($params);

        return view('konsumen.checkout', compact('snapToken', 'cart'));
    }
}
