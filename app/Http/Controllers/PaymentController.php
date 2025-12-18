<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\Config;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$clientKey    = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = config('midtrans.is_sanitized');
        Config::$is3ds        = config('midtrans.is_3ds');
    }

    public function createTransaction(Request $request)
    {
        $amount = $request->amount ?? 20000;
        $name   = $request->name ?? "Donatur";
        $email  = $request->email ?? "test@example.com";

        $params = [
            'transaction_details' => [
                'order_id' => 'INV-' . time(),
                'gross_amount' => $amount,
            ],
            'customer_details' => [
                'first_name' => $name,
                'email'      => $email,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return response()->json([
            'snapToken' => $snapToken,
            'client_key' => config('midtrans.client_key'),
        ]);
    }
}
