<?php

namespace App\Http\Controllers;

use App\Models\Donasi;
use App\Models\Pemasukkan;
use App\Models\Pengeluaran;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class FinanceController extends Controller
{
    public function index()
    {
        /* ===============================
         | CARD DATA
         =============================== */
        $totalTransaksi = Donasi::count();
        $totalPemasukkan = Pemasukkan::sum('jumlah_dana');
        $totalPengeluaran = Pengeluaran::sum('jumlah_dana');
        $saldoKas = $totalPemasukkan - $totalPengeluaran;

        /* ===============================
         | GRAFIK 12 BULAN
         =============================== */
        $labels = [];
        $dataPemasukkan = [];
        $dataPengeluaran = [];
        $dataTransaksi = [];

        for ($i = 11; $i >= 0; $i--) {
            $bulan = Carbon::now()->subMonths($i);

            $labels[] = $bulan->format('M Y');

            $dataPemasukkan[] = Pemasukkan::whereMonth('tanggal', $bulan->month)
                ->whereYear('tanggal', $bulan->year)
                ->sum('jumlah_dana');

            $dataPengeluaran[] = Pengeluaran::whereMonth('tanggal', $bulan->month)
                ->whereYear('tanggal', $bulan->year)
                ->sum('jumlah_dana');

            $dataTransaksi[] = Donasi::whereMonth('created_at', $bulan->month)
                ->whereYear('created_at', $bulan->year)
                ->count();
        }

        return view('finance.dashboard', compact(
            'totalTransaksi',
            'totalPemasukkan',
            'totalPengeluaran',
            'saldoKas',
            'labels',
            'dataPemasukkan',
            'dataPengeluaran',
            'dataTransaksi'
        ));
    }

    /* ===============================
     | TRANSACTION PAGE
     =============================== */
    public function transaction($kategori = null)
    {
        $query = Transaction::with(['donasi.program']);

        if ($kategori) {
            $query->whereHas('donasi.program', function ($q) use ($kategori) {
                $q->where('kategori', $kategori);
            });
        }

        $transactions = $query->latest()->paginate(10);

        // kategori enum (bisa juga ambil dari DB)
        $kategoriList = ['Zakat','Infak','Sedekah','Wakaf','Hibah'];

        return view('finance.transaction', compact(
            'transactions',
            'kategoriList',
            'kategori'
        ));
    }
}
