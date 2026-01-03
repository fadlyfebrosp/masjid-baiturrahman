<?php

namespace App\Http\Controllers;

use App\Models\Donasi;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\Config;
use App\Helpers\Setting;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use App\Mail\DonasiPendingMail;
use App\Mail\DonasiSuccessMail;


class TransactionController extends Controller
{
    /**
     * GET
     * Proses pembayaran Midtrans
     */
    public function pay($donasiId)
    {
        $donasi = Donasi::with('program')->findOrFail($donasiId);

        if ($donasi->status !== 'pending') {
            abort(403, 'Donasi sudah diproses');
        }

        // ==========================
        // MIDTRANS CONFIG
        // ==========================
        $settings = Setting::load();
        $mode     = $settings['midtrans_mode'] ?? 'sandbox';
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
        // CREATE REFERENCE (ORDER ID)
        // ==========================
        $reference = 'DON-' . $donasi->id . '-' . time();

        $transaction = Transaction::create([
            'donasi_id'       => $donasi->id,
            'reference'       => $reference,
            'payment_method'  => null,
            'payment_type'    => null,
            'payment_channel' => null,
            'amount'          => $donasi->nominal,
            'status'          => 'pending',
        ]);

        // ==========================
        // SEND EMAIL (PENDING)
        // ==========================
        if (!empty($donasi->email)) {
            Mail::to($donasi->email)
                ->send(new DonasiPendingMail($donasi, $transaction));
        }

        // ==========================
        // MIDTRANS PAYLOAD
        // ==========================
        $payload = [
            'transaction_details' => [
                'order_id'     => $reference,
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
            'snapToken' => $snapToken,
            'clientKey' => $midtrans['client_key'],
            'reference' => $reference,
        ]);
    }

    /**
     * POST
     * Webhook Midtrans
     */
    public function callback(Request $request)
    {
        $transaction = Transaction::where('reference', $request->order_id)
            ->with('donasi.program')
            ->firstOrFail();

        $donasi = $transaction->donasi;

        // ==========================
        // PREVENT DOUBLE PROCESS
        // ==========================
        if ($transaction->status === 'paid') {
            return response()->json(['status' => 'already_paid']);
        }

        // ==========================
        // DETECT PAYMENT METHOD
        // ==========================
        $paymentType   = $request->payment_type;
        $paymentMethod = null;
        $channel       = null;

        if ($paymentType === 'bank_transfer') {
            $channel = $request->bank
                ?? ($request->va_numbers[0]['bank'] ?? null);

            $paymentMethod = $channel;
        } elseif ($paymentType === 'qris') {
            $paymentMethod = 'qris';
            $channel       = 'qris';
        } elseif (in_array($paymentType, ['gopay', 'shopeepay', 'ovo'])) {
            $paymentMethod = $paymentType;
            $channel       = $paymentType;
        }

        // ==========================
        // UPDATE TRANSACTION
        // ==========================
        $transaction->update([
            'payment_type'    => $paymentType,
            'payment_channel' => $channel,
            'payment_method'  => $paymentMethod,
        ]);

        // ==========================
        // HANDLE STATUS
        // ==========================
        match ($request->transaction_status) {
            'settlement', 'capture' => $this->paid($transaction, $donasi),
            'expire'                => $this->expired($transaction, $donasi),
            'deny', 'cancel'        => $this->failed($transaction, $donasi),
            default                 => null,
        };

        return response()->json(['status' => 'ok']);
    }

    // ==========================
    // STATUS HANDLER
    // ==========================
    private function paid(Transaction $transaction, Donasi $donasi)
    {
        $transaction->update([
            'status'  => 'paid',
            'paid_at' => now(),
        ]);

        $donasi->update([
            'status' => 'paid',
        ]);

        if (!empty($donasi->email)) {
            Mail::to($donasi->email)
                ->send(new DonasiSuccessMail($donasi));
        }
    }
    public function back(Transaction $transaction)
    {
        if ($transaction->status !== 'pending') {
            abort(403, 'Transaksi tidak bisa dilanjutkan');
        }

        $donasi = $transaction->donasi;

        // ==========================
        // MIDTRANS CONFIG
        // ==========================
        $settings = Setting::load();
        $mode     = $settings['midtrans_mode'] ?? 'sandbox';
        $midtrans = $settings['midtrans'][$mode];

        Config::$serverKey    = $midtrans['server_key'];
        Config::$isProduction = $mode === 'production';
        Config::$isSanitized  = true;
        Config::$is3ds        = true;

        // ==========================
        // PAYLOAD (ORDER ID SAMA)
        // ==========================
        $payload = [
            'transaction_details' => [
                'order_id'     => $transaction->reference,
                'gross_amount' => (int) $transaction->amount,
            ],
            'customer_details' => [
                'first_name' => $donasi->nama_donatur,
                'email'      => $donasi->email,
                'phone'      => $donasi->telepon,
            ],
        ];

        // Snap Token BARU (AMAN)
        $snapToken = Snap::getSnapToken($payload);

        return view('midtrans.snap', [
            'snapToken'  => $snapToken,
            'clientKey'  => $midtrans['client_key'],
            'production' => $mode === 'production',
            'reference'  => $transaction->reference,
        ]);
    }


    private function expired(Transaction $transaction, Donasi $donasi)
    {
        $transaction->update(['status' => 'expired']);
        $donasi->update(['status' => 'expired']);
    }

    private function failed(Transaction $transaction, Donasi $donasi)
    {
        $transaction->update(['status' => 'failed']);
        $donasi->update(['status' => 'failed']);
    }
}
