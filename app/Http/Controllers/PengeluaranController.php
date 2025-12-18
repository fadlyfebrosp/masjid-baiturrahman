<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PengeluaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengeluaran::query();

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('tanggal', [
                $request->start_date,
                $request->end_date
            ]);
        }

        $data = $query->orderBy('tanggal', 'desc')->get();

        return view('finance.pengeluaran', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kategori' => 'required|string',
            'jumlah_dana' => 'required|numeric',
        ]);

        Pengeluaran::create($request->all());

        return redirect()
            ->route('finance.pengeluaran.index')
            ->with('success', 'Pengeluaran berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $data = Pengeluaran::findOrFail($id);

        $request->validate([
            'tanggal' => 'required|date',
            'kategori' => 'required|string',
            'jumlah_dana' => 'required|numeric',
        ]);

        $data->update($request->all());

        return redirect()
            ->route('finance.pengeluaran.index')
            ->with('success', 'Pengeluaran berhasil diperbarui');
    }

    public function destroy($id)
    {
        Pengeluaran::findOrFail($id)->delete();

        return redirect()
            ->route('finance.pengeluaran.index')
            ->with('success', 'Pengeluaran berhasil dihapus');
    }
    public function laporan(Request $request)
    {
        $query = Pengeluaran::query();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [
                $request->start_date,
                $request->end_date
            ]);
        }

        $data = $query->orderBy('tanggal', 'asc')->get();
        $total = $data->sum('jumlah_dana');

        return view('finance.laporan.pengeluaran', compact('data', 'total'));
    }

    public function exportPdf(Request $request)
    {
        $query = Pengeluaran::query();
        $isRange = false;

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [
                $request->start_date,
                $request->end_date
            ]);
            $isRange = true;
        }

        $data = $query->orderBy('tanggal', 'asc')->get();
        $total = $data->sum('jumlah_dana');

        $periode = $isRange
            ? $request->start_date . ' s/d ' . $request->end_date
            : 'Semua Data';

        $namaFile = $isRange
            ? 'laporan-pengeluaran-range.pdf'
            : 'laporan-pengeluaran-semua.pdf';

        $pdf = Pdf::loadView('finance.pdf.laporan_pengeluaran', [
            'data' => $data,
            'total' => $total,
            'periode' => $periode,
            'tanggalCetak' => Carbon::now()->format('d M Y')
        ])->setPaper('A4', 'portrait');

        return $pdf->download($namaFile);
    }
}
