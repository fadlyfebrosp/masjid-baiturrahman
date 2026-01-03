<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\KontakInformasi;
use Illuminate\Support\Facades\Storage;

class ResetPasswordCustom extends ResetPassword
{
    private const DEFAULT_LOGO = 'assets/img/Image-not-found.png';
    private const STORAGE_PATH = 'storage/';

    protected function getLogo(): string
    {
        $kontak = KontakInformasi::first();

        if (
            $kontak &&
            $kontak->logo &&
            Storage::disk('public')->exists($kontak->logo)
        ) {
            return asset(self::STORAGE_PATH . $kontak->logo);
        }

        return asset(self::DEFAULT_LOGO);
    }

    public function toMail($notifiable)
    {
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->email,
        ], false));

        return (new MailMessage)
            ->subject('Reset Password Akun Masjid Baiturrahman')
            ->view('auth.email.reset-password', [
                'resetUrl' => $resetUrl,
                'user'     => $notifiable,
                'logo'     => $this->getLogo(),
            ]);
    }
}
