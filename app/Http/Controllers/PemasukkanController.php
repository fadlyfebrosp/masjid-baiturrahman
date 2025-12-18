<?php

namespace App\Http\Controllers;

use App\Models\Pemasukkan;
use Illuminate\Http\Request;

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

        $data = $query->orderBy('tanggal', 'asc')->get();

        $total = $data->sum('jumlah_dana');

        return view('finance.laporanpemasukkan', compact('data', 'total'));
    }
    public function exportPdf(Request $request)
    {
        $query = Pemasukkan::query();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [
                $request->start_date,
                $request->end_date
            ]);
        }

        $data = $query->orderBy('tanggal')->get();
        $total = $data->sum('jumlah_dana');

        $pdf = Pdf::loadView('finance.exports.pemasukkan_pdf', compact('data', 'total'))
                ->setPaper('A4', 'portrait');

        return $pdf->download('laporan-pemasukkan.pdf');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(
            new PemasukkanExport(
                $request->start_date,
                $request->end_date
            ),
            'laporan-pemasukkan.xlsx'
        );
    }
}
