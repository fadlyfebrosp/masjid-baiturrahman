<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Beritadankegiatan;
use App\Models\Donasi;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\KontakInformasi;
use App\Traits\LogActivity;
use App\Models\Pemasukkan;
use App\Models\Pengeluaran;
use Carbon\Carbon;

class HomeController extends Controller
{
    use LogActivity;
    /* ===============================
     | HOMEPAGE
     =============================== */
    public function index(Request $request)
    {
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;
        // TOTAL PEMASUKAN BULAN INI
        $totalPemasukan = Pemasukkan::whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->sum('jumlah_dana');

        // TOTAL PENGELUARAN BULAN INI
        $totalPengeluaran = Pengeluaran::whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->sum('jumlah_dana');

        // SALDO
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        // PERIODE
        $periode = Carbon::now()->translatedFormat('F Y');
        /* ===============================
        | TOTAL DONASI PER KATEGORI
        =============================== */
        $kategoriSummary = Program::query()
        ->select(
            'programs.kategori',
            DB::raw('COALESCE(SUM(programs.target_dana), 0) as total_target'),
            DB::raw('COALESCE(SUM(donasis.nominal), 0) as total_terkumpul')
        )
        ->leftJoin('donasis', function ($join) {
            $join->on('programs.id', '=', 'donasis.program_id')
                ->where('donasis.status', 'paid');
        })
        ->groupBy('programs.kategori')
        ->get()
        ->keyBy('kategori');

        /* ===============================
        | GRAND TOTAL ZISWAF
        =============================== */
        $grandTotal = Donasi::where('status', 'paid')->sum('nominal');

        /* =====================
        | AJAX SEARCH
        ===================== */
        if ($request->wantsJson() && $request->q) {
            $keyword = $request->q;

            $programs = Program::where('judul', 'like', "%{$keyword}%")
                ->limit(5)
                ->get()
                ->map(function ($p) {
                    return [
                        'judul' => $p->judul,
                        'type'  => 'Program',
                        'url'   => route('program.detail', [
                            'kategori' => strtolower($p->kategori),
                            'slug'     => $p->slug,
                        ]),
                    ];
                });

            $berita = Beritadankegiatan::where('judul', 'like', "%{$keyword}%")
                ->limit(5)
                ->get()
                ->map(function ($b) {
                    return [
                        'judul' => $b->judul,
                        'type'  => 'Berita & Kegiatan',
                        'url'   => route('beritadankegiatan.detail', $b->slug),
                    ];
                });

            return response()->json(
                $programs->merge($berita)->values()
            );
        }

        /* =====================
        | NORMAL HOMEPAGE
        ===================== */
        $berita   = Beritadankegiatan::latest()->take(6)->get();
        $programs = Program::withSum(
            ['donasis as terkumpul' => function ($q) {
                $q->where('status', 'paid');
            }],
            'nominal'
        )
        ->withCount([
            'donasis as jumlah_donasi' => function ($q) {
                $q->where('status', 'paid');
            }
        ])
        ->latest()
        ->get();
        $kontak   = KontakInformasi::first();

        $logo = $kontak && $kontak->logo
            ? asset('storage/'.$kontak->logo)
            : asset('assets/img/logo1.png');

        return view('index', compact(
            'berita',
            'programs',
            'logo',
            'kategoriSummary',
            'grandTotal',
            'totalPemasukan',
            'totalPengeluaran',
            'saldoAkhir',
            'periode'
        ));
    }
    public function laporan(Request $request)
    {
        // =========================
        // FILTER BULAN & TAHUN
        // =========================
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        // =========================
        // DATA PEMASUKAN
        // =========================
        $pemasukkans = Pemasukkan::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal')
            ->get();

        // =========================
        // DATA PENGELUARAN
        // =========================
        $pengeluarans = Pengeluaran::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal')
            ->get();

        // =========================
        // TOTAL
        // =========================
        $totalPemasukan   = $pemasukkans->sum('jumlah_dana');
        $totalPengeluaran = $pengeluarans->sum('jumlah_dana');
        $saldoAkhir       = $totalPemasukan - $totalPengeluaran;

        // =========================
        // GRAFIK PER HARI
        // =========================
        $grafik = DB::table('pemasukkans')
            ->select(
                DB::raw('DATE(tanggal) as tanggal'),
                DB::raw('SUM(jumlah_dana) as pemasukan')
            )
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->groupBy('tanggal')
            ->get()
            ->keyBy('tanggal');

        $grafikPengeluaran = DB::table('pengeluarans')
            ->select(
                DB::raw('DATE(tanggal) as tanggal'),
                DB::raw('SUM(jumlah_dana) as pengeluaran')
            )
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->groupBy('tanggal')
            ->get()
            ->keyBy('tanggal');

        $labels = [];
        $dataMasuk = [];
        $dataKeluar = [];

        foreach ($grafik as $tgl => $item) {
            $labels[]     = Carbon::parse($tgl)->format('d M');
            $dataMasuk[]  = $item->pemasukan;
            $dataKeluar[] = $grafikPengeluaran[$tgl]->pengeluaran ?? 0;
        }

        return view('laporan.index', compact(
            'pemasukkans',
            'pengeluarans',
            'totalPemasukan',
            'totalPengeluaran',
            'saldoAkhir',
            'labels',
            'dataMasuk',
            'dataKeluar',
            'bulan',
            'tahun'
        ));
    }
    /* ===============================
     | SHOW PROFILE (READ ONLY)
     =============================== */
    public function profile(Request $request)
    {
        $this->logActivity($request, 'Lihat Profil');

        return view('pages.profile.show', [
            'user' => Auth::user(),
        ]);
    }

    /* ===============================
     | EDIT PROFILE
     =============================== */
    public function editProfile(Request $request)
    {
        $this->logActivity($request, 'Buka Form Edit Profil');

        return view('pages.profile.edit', [
            'user' => Auth::user(),
        ]);
    }


    /* ===============================
     | UPDATE PROFILE
     =============================== */
    public function updateProfile(Request $request)
    {
        $this->logActivity($request, 'Update Profil');

        /** @var User $user */
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email,' . $user->id,
            'phone'  => 'nullable|string|max:20',
            'gender' => 'nullable|in:Laki-laki,Perempuan',
            'image'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }

            $user->image = $request->file('image')->store('profile', 'public');
        }

        $user->fill([
            'name'   => $request->name,
            'email'  => $request->email,
            'phone'  => $request->phone,
            'gender' => $request->gender,
        ]);

        $user->save();

        return redirect()
            ->route('profile')
            ->with('success', 'Profil berhasil diperbarui');
    }
}
