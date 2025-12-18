<?php

namespace App\Http\Controllers;

use App\Models\Pemasukkan;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;



class PemasukkanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pemasukkan::query();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [
                $request->start_date,
                $request->end_date
            ]);
        }

        $data = $query->latest()->get();

        return view('finance.pemasukkan', compact('data'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'sumber_dana' => 'required|string|max:255',
            'jumlah_dana' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        Pemasukkan::create($validated);

        return redirect()
            ->route('finance.pemasukkan.index')
            ->with('success', 'Data pemasukkan berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'sumber_dana' => 'required|string|max:255',
            'jumlah_dana' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        Pemasukkan::findOrFail($id)->update($validated);

        return redirect()
            ->route('finance.pemasukkan.index')
            ->with('success', 'Data pemasukkan berhasil diupdate');
    }

    public function destroy($id)
    {
        Pemasukkan::findOrFail($id)->delete();

        return redirect()
            ->route('finance.pemasukkan.index')
            ->with('success', 'Data pemasukkan berhasil dihapus');
    }
    public function laporan(Request $request)
    {
        $query = Pemasukkan::query();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [
                $request->start_date,
                $request->end_date
            ]);
        }

        $dataPemasukkan = $query->orderBy('tanggal')->get();
        $totalPemasukkan = $dataPemasukkan->sum('jumlah_dana');

        return view('finance.laporan.pemasukkan', compact(
            'dataPemasukkan',
            'totalPemasukkan'
        ));
    }
    public function laporanGabungan(Request $request)
    {
        $pemasukkan = Pemasukkan::query();
        $pengeluaran = Pengeluaran::query();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $pemasukkan->whereBetween('tanggal', [
                $request->start_date,
                $request->end_date
            ]);
            $pengeluaran->whereBetween('tanggal', [
                $request->start_date,
                $request->end_date
            ]);
        }

        $dataPemasukkan = $pemasukkan->orderBy('tanggal')->get();
        $dataPengeluaran = $pengeluaran->orderBy('tanggal')->get();

        $totalPemasukkan = $dataPemasukkan->sum('jumlah_dana');
        $totalPengeluaran = $dataPengeluaran->sum('jumlah_dana');
        $saldoAkhir = $totalPemasukkan - $totalPengeluaran;

        return view('finance.laporan.laporankeuangan', compact(
            'dataPemasukkan',
            'dataPengeluaran',
            'totalPemasukkan',
            'totalPengeluaran',
            'saldoAkhir'
        ));
    }
    public function exportPdfGabungan(Request $request)
    {
        $pemasukkan = Pemasukkan::query();
        $pengeluaran = Pengeluaran::query();

        $isRange = false;

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $pemasukkan->whereBetween('tanggal', [
                $request->start_date,
                $request->end_date
            ]);

            $pengeluaran->whereBetween('tanggal', [
                $request->start_date,
                $request->end_date
            ]);

            $isRange = true;
        }

        $dataPemasukkan = $pemasukkan->orderBy('tanggal')->get();
        $dataPengeluaran = $pengeluaran->orderBy('tanggal')->get();

        $totalPemasukkan = $dataPemasukkan->sum('jumlah_dana');
        $totalPengeluaran = $dataPengeluaran->sum('jumlah_dana');
        $saldoAkhir = $totalPemasukkan - $totalPengeluaran;

        $periode = $isRange
            ? $request->start_date . ' s/d ' . $request->end_date
            : 'Semua Data';

        $pdf = Pdf::loadView(
            'finance.laporan.pdf.laporan_gabungan',
            [
                'dataPemasukkan' => $dataPemasukkan,
                'dataPengeluaran' => $dataPengeluaran,
                'totalPemasukkan' => $totalPemasukkan,
                'totalPengeluaran' => $totalPengeluaran,
                'saldoAkhir' => $saldoAkhir,
                'periode' => $periode,
                'tanggalCetak' => Carbon::now()->format('d M Y'),
            ]
        )->setPaper('A4', 'portrait');

        return $pdf->download('laporan-keuangan-masjid.pdf');
    }
    public function exportExcelGabungan(Request $request)
    {
        $pemasukkan = Pemasukkan::query();
        $pengeluaran = Pengeluaran::query();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $pemasukkan->whereBetween('tanggal', [
                $request->start_date,
                $request->end_date
            ]);

            $pengeluaran->whereBetween('tanggal', [
                $request->start_date,
                $request->end_date
            ]);
        }

        return Excel::download(
            new \App\Exports\LaporanKeuanganGabunganExport(
                $pemasukkan->orderBy('tanggal')->get(),
                $pengeluaran->orderBy('tanggal')->get()
            ),
            'laporan-keuangan-masjid.xlsx'
        );
    }

    public function exportPdf(Request $request)
    {
        $pemasukkan = Pemasukkan::query();
        $pengeluaran = Pengeluaran::query();

        $isRange = false;

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $pemasukkan->whereBetween('tanggal', [$request->start_date, $request->end_date]);
            $pengeluaran->whereBetween('tanggal', [$request->start_date, $request->end_date]);
            $isRange = true;
        }

        $dataPemasukkan = $pemasukkan->orderBy('tanggal')->get();
        $dataPengeluaran = $pengeluaran->orderBy('tanggal')->get();

        $totalPemasukkan = $dataPemasukkan->sum('jumlah_dana');
        $totalPengeluaran = $dataPengeluaran->sum('jumlah_dana');
        $saldoAkhir = $totalPemasukkan - $totalPengeluaran;

        $periode = $isRange
            ? $request->start_date . ' s/d ' . $request->end_date
            : 'Semua Data';

        $pdf = Pdf::loadView(
            'finance.laporan.pdf.laporan_pemasukkan',
            [
            'dataPemasukkan' => $dataPemasukkan,
            'dataPengeluaran' => $dataPengeluaran,
            'totalPemasukkan' => $totalPemasukkan,
            'totalPengeluaran' => $totalPengeluaran,
            'saldoAkhir' => $saldoAkhir,
            'periode' => $periode,
            'tanggalCetak' => Carbon::now()->format('d M Y')
        ])->setPaper('A4', 'portrait');

        return $pdf->download('laporan-keuangan-masjid.pdf');
    }
}
