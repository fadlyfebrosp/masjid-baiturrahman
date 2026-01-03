<?php

namespace App\Http\Controllers;

use App\Models\KontakInformasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Traits\LogActivity;
use Illuminate\Support\Facades\Storage;

class ContactController extends Controller
{
    use LogActivity;
    public function index(Request $request)
    {
        $this->logActivity($request, 'Buka Halaman Hubungi Kami');

        // Ambil data kontak
        $kontak = KontakInformasi::first();

        // Default logo
        $logo = asset('assets/img/Image-not-found.png');

        if (
            $kontak &&
            $kontak->logo &&
            Storage::disk('public')->exists($kontak->logo)
        ) {
            $logo = asset('storage/' . $kontak->logo);
        }

        return view('pages.hubungikami', compact('kontak', 'logo'));
    }
    public function send(Request $request)
    {
        $this->logActivity($request, 'Kirim Pesan Kontak');

        // Validasi input
        $request->validate([
            'nama'    => 'required|string|max:255',
            'email'   => 'required|email',
            'no_telp' => 'required|string|max:20',
            'judul'   => 'required|string|max:255',
            'pesan'   => 'required|string',
        ]);

        // Kirim email
        Mail::send('emails.contact', [
            'nama'    => $request->nama,
            'email'   => $request->email,
            'no_telp' => $request->no_telp,
            'judul'   => $request->judul,
            'pesan'   => $request->pesan,
        ], function ($message) use ($request) {
            $message->to('starseed768@gmail.com')
                    ->subject('Pesan dari Website: ' . $request->judul)
                    ->replyTo($request->email, $request->nama);
        });

        return back()->with('success', 'Pesan berhasil dikirim!');
    }
}
