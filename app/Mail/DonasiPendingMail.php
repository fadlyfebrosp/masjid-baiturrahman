<?php

namespace App\Mail;

use App\Models\Donasi;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DonasiPendingMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Donasi $donasi,
        public Transaction $transaction
    ) {}

    public function build()
    {
        return $this->subject('Silakan Selesaikan Pembayaran Donasi Anda')
            ->view('midtrans.pending');
    }
}
