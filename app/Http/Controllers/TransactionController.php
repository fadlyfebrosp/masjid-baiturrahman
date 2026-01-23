<?php

namespace App\Http\Controllers;

use App\Models\Donasi;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Midtrans\Snap;
use Midtrans\Config;
use App\Helpers\Setting;
use App\Mail\DonasiPendingMail;
use App\Mail\DonasiSuccessMail;

class TransactionController extends Controller
{
    /* =====================================================
     * START PAYMENT (CREATE TRANSACTION + SNAP)
     * ===================================================== */
    public function pay(int $donasiId)
    {
        $donasi = Donasi::with('program')->findOrFail($donasiId);
        abort_if($donasi->status !== 'pending', 403);

        $settings = Setting::load();
        $mode     = $settings['midtrans_mode'] ?? 'sandbox';
        $midtrans = $settings['midtrans'][$mode] ?? [];

        $reference = 'DON-' . $donasi->id . '-' . time();

        $transaction = Transaction::create([
            'donasi_id' => $donasi->id,
            'reference' => $reference,
            'amount'    => $donasi->nominal,
            'status'    => 'pending',
        ]);

        if ($donasi->email) {
            Mail::to($donasi->email)->send(
                new DonasiPendingMail($donasi, $transaction)
            );
        }

        $snapToken = $this->createSnapToken(
            $transaction,
            $donasi,
            $midtrans,
            $mode
        );

        return view('midtrans.snap', [
            'snapToken'  => $snapToken,
            'clientKey'  => $midtrans['client_key'] ?? '',
            'production' => $mode === 'production',
            'reference'  => $transaction->reference,
        ]);
    }

    /* =====================================================
     * BACK TO PAYMENT (FROM PENDING PAGE)
     * ===================================================== */
    public function back(Transaction $transaction)
    {
        if ($transaction->status === 'paid') {
            return redirect()->route('payment.success', $transaction->reference);
        }

        if (in_array($transaction->status, ['failed', 'expired'])) {
            return redirect()->route('payment.failed', $transaction->reference);
        }

        $settings = Setting::load();
        $mode     = $settings['midtrans_mode'] ?? 'sandbox';
        $midtrans = $settings['midtrans'][$mode] ?? [];

        $snapToken = $this->createSnapToken(
            $transaction,
            $transaction->donasi,
            $midtrans,
            $mode
        );

        return view('midtrans.snap', [
            'snapToken'  => $snapToken,
            'clientKey'  => $midtrans['client_key'] ?? '',
            'production' => $mode === 'production',
            'reference'  => $transaction->reference,
        ]);
    }

    /* =====================================================
     * PENDING PAGE
     * ===================================================== */
    public function pending(string $reference)
    {
        $transaction = Transaction::where('reference', $reference)->firstOrFail();

        return view('midtrans.pending', compact('transaction'));
    }

    /* =====================================================
     * STATUS POLLING (AJAX)
     * ===================================================== */
    public function status(string $reference)
    {
        $transaction = Transaction::where('reference', $reference)->firstOrFail();

        return response()->json([
            'status' => $transaction->status,
        ]);
    }

    /* =====================================================
     * SUCCESS PAGE
     * ===================================================== */
    public function success(string $reference)
    {
        $transaction = Transaction::where('reference', $reference)->firstOrFail();
        abort_if($transaction->status !== 'paid', 403);

        return view('midtrans.success', compact('transaction'));
    }

    /* =====================================================
     * FAILED / EXPIRED PAGE
     * ===================================================== */
    public function failed(string $reference)
    {
        $transaction = Transaction::where('reference', $reference)->firstOrFail();

        abort_if(
            !in_array($transaction->status, ['failed', 'expired']),
            403
        );

        return view('midtrans.failed', compact('transaction'));
    }

    /* =====================================================
     * MIDTRANS CALLBACK (WEBHOOK)
     * ===================================================== */
    public function callback(Request $request)
    {
        Log::info('MIDTRANS CALLBACK', $request->all());

        $settings = Setting::load();
        $mode     = $settings['midtrans_mode'] ?? 'sandbox';
        $midtrans = $settings['midtrans'][$mode] ?? [];

        $serverKey = $midtrans['server_key'] ?? '';

        $signature = hash(
            'sha512',
            $request->order_id .
                $request->status_code .
                $request->gross_amount .
                $serverKey
        );

        if ($signature !== $request->signature_key) {
            Log::error('MIDTRANS INVALID SIGNATURE', $request->all());
            abort(403);
        }

        $transaction = Transaction::where('reference', $request->order_id)
            ->with('donasi')
            ->firstOrFail();

        if ($transaction->status === 'paid') {
            return response()->json(['ok']);
        }

        match ($request->transaction_status) {
            'settlement', 'capture' => $this->paid($transaction),
            'expire'                => $this->expired($transaction),
            'deny', 'cancel'        => $this->markFailed($transaction),
            default                 => null,
        };

        return response()->json(['ok']);
    }

    /* =====================================================
     * SNAP TOKEN CREATOR
     * ===================================================== */
    private function createSnapToken(
        Transaction $transaction,
        Donasi $donasi,
        array $midtrans,
        string $mode
    ) {
        Config::$serverKey    = $midtrans['server_key'] ?? '';
        Config::$isProduction = $mode === 'production';
        Config::$isSanitized  = true;
        Config::$is3ds        = true;

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

        return Snap::getSnapToken($payload);
    }

    /* =====================================================
     * STATUS HANDLERS
     * ===================================================== */
    private function paid(Transaction $transaction)
    {
        $transaction->update([
            'status'  => 'paid',
            'paid_at' => now(),
        ]);

        $transaction->donasi->update(['status' => 'paid']);

        if ($transaction->donasi->email) {
            Mail::to($transaction->donasi->email)
                ->send(new DonasiSuccessMail($transaction->donasi));
        }
    }

    private function expired(Transaction $transaction)
    {
        $transaction->update(['status' => 'expired']);
        $transaction->donasi->update(['status' => 'expired']);
    }

    private function markFailed(Transaction $transaction)
    {
        $transaction->update(['status' => 'failed']);
        $transaction->donasi->update(['status' => 'failed']);
    }
}
