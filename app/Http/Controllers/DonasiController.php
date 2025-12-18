<?php

namespace App\Http\Controllers;

use App\Models\Donasi;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonasiController extends Controller
{
    public function indexByKategori($kategori)
    {
        $kategori = ucfirst(strtolower($kategori));

        $data = Program::where('kategori', $kategori)
            ->withSum(['donasis as terkumpul' => function ($q) {
                $q->where('status', 'paid');
            }], 'nominal')
            ->withCount(['donasis as jumlah_donasi' => function ($q) {
                $q->where('status', 'paid');
            }])
            ->latest()
            ->paginate(9);

        return view('program.donasi.index', [
            'kategori' => $kategori,
            'data'     => $data
        ]);
    }
    /**
     * GET
     * Halaman checkout (form identitas donatur)
     */
    public function donateNow(Request $request, $kategori, $slug)
    {
        $program = Program::where('slug', $slug)->firstOrFail();
        $nominal = session('nominal');

        abort_if(!$nominal, 404);

        return view('program.donasi.detail.donate-now.checkout', compact(
            'program',
            'nominal'
        ));
    }

    /**
     * POST
     * Simpan nominal ke session (tanpa DB)
     */
    public function donateNowPost(Request $request, $kategori, $slug)
    {
        $request->validate([
            'nominal' => 'required|integer|min:1000',
        ], [
            'nominal.required' => 'Nominal wajib diisi',
            'nominal.min'      => 'Minimal donasi Rp 1.000',
        ]);

        session(['nominal' => $request->nominal]);

        return redirect()->route('donasi.form', [$kategori, $slug]);
    }

    /**
     * POST
     * SIMPAN DONASI SAJA (BELUM PAYMENT)
     */
    public function store(Request $request, $kategori, $slug)
    {
        $validated = $request->validate([
            'nama_donatur' => 'required|string|max:100',
            'telepon'      => 'required|string|max:20',
            'email'        => 'nullable|email',
            'nominal'      => 'required|integer|min:1000',
        ]);

        $program = Program::where('slug', $slug)->firstOrFail();

        $donasi = Donasi::create([
            'program_id'   => $program->id,
            'user_id'      => Auth::id(),
            'nama_donatur' => $validated['nama_donatur'],
            'email'        => $validated['email'],
            'telepon'      => $validated['telepon'],
            'anonim'       => $request->boolean('anonim'),
            'nominal'      => $validated['nominal'],
            'deskripsi'    => $request->deskripsi,
            'status'       => 'pending',
        ]);

        // hapus session nominal
        session()->forget('nominal');

        // redirect ke payment
        return redirect()->route('transaction.pay', $donasi->id);
    }
}
