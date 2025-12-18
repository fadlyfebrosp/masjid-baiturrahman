<?php

namespace App\Http\Controllers;

use App\Models\KontakInformasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Traits\LogActivity;

class ContactController extends Controller
{
    use LogActivity;
    public function index(Request $request)
    {
        $this->logActivity($request, 'Buka Halaman Hubungi Kami');

        // Ambil 1 data identitas aplikasi
        $kontak = KontakInformasi::first();

        return view('pages.hubungikami', compact('kontak'));
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
