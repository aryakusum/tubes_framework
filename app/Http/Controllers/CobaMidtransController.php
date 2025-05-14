<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CobaMidtransController extends Controller
{
    public function cekmidtrans(Request $request)
    {
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $item1_details = [
            'id' => 'a1',
            'price' => 10000,
            'quantity' => 3,
            'name' => "Apple"
        ];

        $item2_details = [
            'id' => 'a2',
            'price' => 20000,
            'quantity' => 1,
            'name' => "Orange"
        ];

        $item_details = [$item1_details, $item2_details];

        $enable_payments = ["bca_va", "bni_va"];

        $params = [
            'transaction_details' => [
                'order_id' => rand(),
                'gross_amount' => 50000,
            ],
            'customer_details' => [
                'first_name' => 'Tina',
                'last_name' => 'Toon',
                'email' => 'nikita@gmail.com',
                'phone' => '0821142334',
            ],
            'item_details' => $item_details,
            'enabled_payments' => $enable_payments,
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        return view('midtrans.viewsampel', [
            'snap_token' => $snapToken,
        ]);
    }
}
