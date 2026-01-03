<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storedonasi_offlineRequest;
use App\Http\Requests\Updatedonasi_offlineRequest;
use App\Models\DonasiOffline;
use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\Contactdonasioffline;
use Illuminate\Support\Str;


class DonasiOfflineController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $query = DonasiOffline::with(['program', 'contact'])
            ->when($search, function ($q) use ($search) {
                $q->whereHas('contact', function ($c) use ($search) {
                    $c->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhere('kode_transaksi', 'like', "%{$search}%");
            });

        // ================= DATA TABLE =================
        $donasi = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

       $totalDiterima = DonasiOffline::where('status', 'SELESAI')
            ->sum('nominal');

        // TOTAL UANG DONASI PROSES + PENDING
        $totalProspek = DonasiOffline::whereIn('status', ['PROSES', 'PENDING'])
            ->sum('nominal');

        // TOTAL UANG DONASI GAGAL
        $totalGagal = DonasiOffline::where('status', 'BATAL')
            ->sum('nominal');

        // TOTAL KONTAK
        $totalContact = Contactdonasioffline::count();

        return view('admin.donasioffline.index', compact(
            'donasi',
            'totalDiterima',
            'totalProspek',
            'totalGagal',
            'totalContact'
        ));
    }
    public function create()
    {
        $programs = Program::select(
            'id',
            'kategori',
            'sub_kategori',
            'judul',
            'foto'
        )->get()
        ->map(function ($p) {
            return [
                'id'           => $p->id,
                'kategori'     => $p->kategori,
                'sub_kategori' => $p->sub_kategori,
                'judul'        => $p->judul,
                'foto_url'     => $p->foto
                    ? asset('storage/' . $p->foto)
                    : asset('assets/img/Image-not-found.png'),
                'sisa_hari'    => null, // aman
            ];
        });

        $contacts = Contactdonasioffline::select(
            'id',
            'name',
            'email',
            'phone',
            'gender'
        )->latest()->get();

        return view('admin.donasioffline.tambah', [
            'kategoriProgram' => Program::select('kategori')->distinct()->get(),
            'programs'        => $programs,
            'contacts'        => $contacts,
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'program_id'                 => 'required|exists:programs,id',
            'contactdonasioffline_id'    => 'required|exists:contactdonasiofflines,id',
            'nominal'                    => 'required|numeric|min:1000',
            'metode_pembayaran'          => 'required',
            'tanggal_transaksi'          => 'required|date',
            'status'                     => 'required',
        ]);

        DonasiOffline::create([
            'program_id'              => $request->program_id,
            'contactdonasioffline_id' => $request->contactdonasioffline_id,
            'nominal'                 => $request->nominal,
            'metode_pembayaran'       => $request->metode_pembayaran,
            'tanggal_transaksi'       => $request->tanggal_transaksi,
            'kode_transaksi'          => 'DO-' . strtoupper(Str::random(10)),
            'catatan'                 => $request->catatan,
            'status'                  => $request->status,
        ]);

        return redirect()
            ->route('admin.donasioffline.index')
            ->with('success', 'Donasi offline berhasil ditambahkan');
    }
    public function edit(DonasiOffline $donasiOffline)
    {
        return view('admin.donasioffline.edit', [
            'donasi'          => $donasiOffline->load(['program', 'contact']),
            'kategoriProgram' => Program::select('kategori')->distinct()->get(),
            'programs'        => Program::all(),
            'contacts'        => Contactdonasioffline::all(),
        ]);
    }
}
