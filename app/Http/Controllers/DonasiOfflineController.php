<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storedonasi_offlineRequest;
use App\Http\Requests\Updatedonasi_offlineRequest;
use App\Models\DonasiOffline;
use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\Contactdonasioffline;
use App\Models\Donasi;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


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
        $programs = Program::withSum([
                'donasis as online_terkumpul' => fn ($q) => $q->where('status', 'paid')
            ], 'nominal')
            ->get()
            ->map(function ($p) {

                // 🔥 DONASI OFFLINE SELESAI
                $offline = DonasiOffline::where('program_id', $p->id)
                    ->where('status', 'SELESAI')
                    ->sum('nominal');

                $terkumpul = ($p->online_terkumpul ?? 0) + $offline;

                // 🔥 SISA HARI
                if ($p->open_goals || !$p->target_waktu) {
                    $sisaHari = 'Tanpa batas waktu';
                } else {
                    $hari = floor(now()->diffInRealDays($p->target_waktu, false));
                    $sisaHari = $hari > 0 ? $hari.' hari lagi' : 'Berakhir';
                }

                return [
                    'id'           => $p->id,
                    'kategori'     => $p->kategori,
                    'sub_kategori' => $p->sub_kategori,
                    'judul'        => $p->judul,
                    'target_dana'  => $p->target_dana ?? 0,
                    'terkumpul'    => $terkumpul,
                    'target_waktu' => $p->target_waktu,
                    'open_goals'   => $p->open_goals,
                    'foto_url'     => $p->foto
                        ? asset('storage/'.$p->foto)
                        : asset('assets/img/Image-not-found.png'),
                ];
            });

        return view('admin.donasioffline.tambah', [
            'kategoriProgram' => Program::select('kategori')->distinct()->get(),
            'programs'        => $programs,
            'contacts'        => Contactdonasioffline::all(),
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'program_id'              => 'required|exists:programs,id',
            'contactdonasioffline_id' => 'required|exists:contactdonasiofflines,id',
            'nominal'                 => 'required|numeric|min:1000',
            'metode_pembayaran'       => 'required',
            'tanggal_transaksi'       => 'required|date',
            'status'                  => 'required',
        ]);

        DB::transaction(function () use ($request) {

            $contact = Contactdonasioffline::findOrFail(
                $request->contactdonasioffline_id
            );

            // 1️⃣ Simpan OFFLINE
            $offline = DonasiOffline::create([
                'program_id'              => $request->program_id,
                'contactdonasioffline_id' => $request->contactdonasioffline_id,
                'nominal'                 => $request->nominal,
                'metode_pembayaran'       => $request->metode_pembayaran,
                'tanggal_transaksi'       => $request->tanggal_transaksi,
                'kode_transaksi'          => 'DO-' . strtoupper(Str::random(10)),
                'status'                  => $request->status,
            ]);

            // 2️⃣ JIKA SELESAI → MASUK DONASI ONLINE
            if ($request->status === 'SELESAI') {

                $donasi = Donasi::create([
                    'program_id'   => $request->program_id,
                    'nama_donatur' => $contact->name,
                    'email'        => $contact->email,
                    'telepon'      => $contact->phone,
                    'anonim'       => false,
                    'nominal'      => $request->nominal,
                    'status'       => 'paid',
                ]);

                // 3️⃣ HUBUNGKAN (opsional tapi rapi)
                $offline->update([
                    'donasi_id' => $donasi->id
                ]);
            }
        });

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
