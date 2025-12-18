<?php

namespace App\Http\Controllers;

use App\Models\Donasi;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\Config;
use App\Helpers\Setting;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    /**
     * GET
     * Proses pembayaran Midtrans
     */
    public function pay($donasiId)
    {
        $donasi = Donasi::findOrFail($donasiId);

        if ($donasi->status !== 'pending') {
            abort(403, 'Donasi sudah diproses');
        }

        // ==========================
        // MIDTRANS CONFIG
        // ==========================
        $settings = Setting::load();
        $mode = $settings['midtrans_mode'] ?? 'sandbox';
        $midtrans = $settings['midtrans'][$mode] ?? null;

        if (!$midtrans || empty($midtrans['server_key'])) {
            throw ValidationException::withMessages([
                'midtrans' => 'Credential Midtrans belum diatur admin',
            ]);
        }

        Config::$serverKey    = $midtrans['server_key'];
        Config::$isProduction = $mode === 'production';
        Config::$isSanitized  = true;
        Config::$is3ds        = true;

        // ==========================
        // CREATE TRANSACTION
        // ==========================
        $orderId = 'DON-' . $donasi->id . '-' . time();

        Transaction::create([
            'donasi_id'      => $donasi->id,
            'reference'      => $orderId,
            'payment_method' => 'midtrans',
            'amount'         => $donasi->nominal,
            'status'         => 'pending',
        ]);

        // ==========================
        // MIDTRANS PAYLOAD
        // ==========================
        $payload = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $donasi->nominal,
            ],
            'customer_details' => [
                'first_name' => $donasi->nama_donatur,
                'email'      => $donasi->email,
                'phone'      => $donasi->telepon,
            ],
        ];

        $snapToken = Snap::getSnapToken($payload);

        return view('midtrans.snap', [
            'snapToken'  => $snapToken,
            'clientKey'  => $midtrans['client_key'],
            'production' => $mode === 'production',
        ]);
    }

    /**
     * POST
     * Webhook Midtrans
     */
    public function callback(Request $request)
    {
        $transaction = Transaction::where('reference', $request->order_id)->firstOrFail();
        $donasi = $transaction->donasi;

        $paymentType = $request->payment_type; // bank_transfer, qris, ewallet
        $channel = null;

        if ($paymentType === 'bank_transfer') {
            $channel = $request->bank
                ?? ($request->va_numbers[0]['bank'] ?? null);
        }

        if ($paymentType === 'qris') {
            $channel = 'qris';
        }

        if ($paymentType === 'gopay') {
            $channel = 'gopay';
        }

        $transaction->update([
            'payment_type'    => $paymentType,
            'payment_channel' => $channel,
        ]);

        match ($request->transaction_status) {
            'settlement', 'capture' => $this->paid($transaction, $donasi),
            'expire'                => $this->expired($transaction, $donasi),
            'deny', 'cancel'        => $this->failed($transaction, $donasi),
            default                 => null,
        };

        return response()->json(['status' => 'ok']);
    }

    private function paid($transaction, $donasi)
    {
        $transaction->update([
            'status'  => 'paid',
            'paid_at'=> now(),
        ]);

        $donasi->update(['status' => 'paid']);
    }

    private function expired($transaction, $donasi)
    {
        $transaction->update(['status' => 'expired']);
        $donasi->update(['status' => 'expired']);
    }

    private function failed($transaction, $donasi)
    {
        $transaction->update(['status' => 'failed']);
        $donasi->update(['status' => 'failed']);
    }
}
