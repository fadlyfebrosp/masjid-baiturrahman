<?php

namespace App\Mail;

use App\Models\Donasi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DonasiSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Donasi $donasi) {}

    public function build()
    {
        $kategori = strtolower($this->donasi->program->kategori);

        $subject = match ($kategori) {
            'zakat'    => 'Terima Kasih Telah Menunaikan Zakat',
            'infaq'    => 'Terima Kasih Telah Berinfaq',
            'sedekah'  => 'Terima Kasih Telah Bersedekah',
            'wakaf'    => 'Terima Kasih Telah Berwakaf',
            'hibah'    => 'Terima Kasih Telah Memberikan Hibah',
            default    => 'Terima Kasih Atas Donasi Anda',
        };

        return $this->subject($subject)
            ->view('midtrans.success');
    }
}
