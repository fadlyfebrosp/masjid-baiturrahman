<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\BeritaDanKegiatan;
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
use App\Models\Transaction;
use Carbon\Carbon;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class HomeController extends Controller
{
    use LogActivity;
    private const DEFAULT_LOGO = 'assets/img/Image-not-found.png';
    private const STORAGE_PATH = 'storage/';
    private function getLogo(): string
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
    public function index(Request $request)
    {
        /* ===============================
        | DASHBOARD RINGKASAN
        =============================== */
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;

        $totalPemasukan = Pemasukkan::whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->sum('jumlah_dana');

        $totalPengeluaran = Pengeluaran::whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->sum('jumlah_dana');

        $saldoAkhir = $totalPemasukan - $totalPengeluaran;
        $periode    = Carbon::now()->translatedFormat('F Y');

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

        /* ===============================
        | AJAX SEARCH
        =============================== */
        if ($request->wantsJson() && $request->q) {
            $keyword = $request->q;

            $programs = Program::where('judul', 'like', "%{$keyword}%")
                ->limit(5)
                ->get()
                ->map(fn($p) => [
                    'judul' => $p->judul,
                    'type'  => 'Program',
                    'url'   => route('program.detail', [
                        'kategori' => strtolower($p->kategori),
                        'slug'     => $p->slug,
                    ]),
                ]);

            $berita = BeritaDankegiatan::where('judul', 'like', "%{$keyword}%")
                ->limit(5)
                ->get()
                ->map(fn($b) => [
                    'judul' => $b->judul,
                    'type'  => 'Berita & Kegiatan',
                    'url'   => route('beritadankegiatan.detail', $b->slug),
                ]);

            return response()->json(
                $programs->merge($berita)->values()
            );
        }

        /* ===============================
        | DATA HOMEPAGE
        =============================== */
        $berita   = BeritaDankegiatan::latest()->take(6)->get();

        $programs = Program::withSum(
            ['donasis as terkumpul' => fn($q) => $q->where('status', 'paid')],
            'nominal'
        )
            ->withCount([
                'donasis as jumlah_donasi' => fn($q) => $q->where('status', 'paid')
            ])
            ->latest()
            ->get();

        /* ===============================
        | INVOICE (OPTIONAL)
        =============================== */
        $transaction = null;

        if ($request->filled('reference')) {
            $transaction = Transaction::with('donasi.program')
                ->where('reference', $request->reference)
                ->where('status', 'paid')
                ->first();
        }
        $logo = $this->getLogo();

        return view('index', compact(
            'logo',
            'transaction',
            'berita',
            'programs',
            'kategoriSummary',
            'grandTotal',
            'totalPemasukan',
            'totalPengeluaran',
            'saldoAkhir',
            'periode'
        ));
    }

    public function kalkulator(Request $request)
    {
        $logo = $this->getLogo();
        return view('program.kalkulatorzakat', compact(
            'logo',
        ));
    }
    public function tentangkami(Request $request)
    {
        $logo = $this->getLogo();
        return view('pages.about', compact(
            'logo',
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
        $logo = $this->getLogo();

        return view('laporan.index', compact(
            'logo',
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
        $logo = $this->getLogo();
        return view('pages.profile.show', [
            'user' => Auth::user(),
            'logo' => $logo
        ]);
    }

    /* ===============================
    | EDIT PROFILE
    =============================== */
    public function editProfile(Request $request)
    {
        $this->logActivity($request, 'Buka Form Edit Profil');
        $logo = $this->getLogo();

        return view('pages.profile.edit', [
            'user' => Auth::user(),
            'logo' => $logo
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

        $logo = $this->getLogo();
        return redirect()
            ->route('profile', ['logo' => $logo])
            ->with('success', 'Profil berhasil diperbarui');
    }
}
