<?php

namespace App\Mail;

use App\Models\Donasi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DonasiSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Donasi $donasi) {}

    public function build()
    {
        $kategori = strtolower($this->donasi->program->kategori);

        $subject = match ($kategori) {
            'zakat'  => 'Terima Kasih Telah Menunaikan Zakat',
            'wakaf'  => 'Terima Kasih Telah Berwakaf',
            default  => 'Terima Kasih Telah Berinfaq',
        };

        return $this->subject($subject)
            ->view('midtrans.success');
    }
}
