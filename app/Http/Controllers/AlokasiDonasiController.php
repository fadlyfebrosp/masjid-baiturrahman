<?php

namespace App\Http\Controllers;

use App\Models\AlokasiDonasi;
use App\Models\Program;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AlokasiDonasiController extends Controller
{
    public function index(Request $request)
    {
        $programId = $request->program_id;
        $kategori  = $request->kategori;
        $start     = $request->start_date;
        $end       = $request->end_date;

        $query = AlokasiDonasi::with('program');

        if ($programId) {
            $query->where('program_id', $programId);
        }

        if ($kategori) {
            $query->whereHas('program', fn ($q) =>
                $q->where('kategori', $kategori)
            );
        }

        if ($start && $end) {
            $query->whereBetween('tanggal', [
                Carbon::parse($start)->startOfDay(),
                Carbon::parse($end)->endOfDay(),
            ]);
        }

        $alokasi = $query->latest('tanggal')->paginate(10)->withQueryString();
        $totalAlokasi = (clone $query)->sum('jumlah');

        $programs = Program::orderBy('judul')->get();
        $kategoriList = ['Zakat', 'Infak', 'Sedekah', 'Wakaf', 'Hibah'];

        return view('finance.alokasidonasi.index', compact(
            'alokasi',
            'totalAlokasi',
            'programs',
            'kategoriList'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'program_id'    => 'required|exists:programs,id',
            'nama_kegiatan' => 'required|string|max:255',
            'jumlah'        => 'required|numeric|min:1',
            'tanggal'       => 'required|date',
            'keterangan'    => 'nullable|string',
        ]);

        AlokasiDonasi::create($request->all());

        return back()->with('success', 'Alokasi berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $alokasi = AlokasiDonasi::findOrFail($id);

        $request->validate([
            'program_id'    => 'required|exists:programs,id',
            'nama_kegiatan' => 'required|string|max:255',
            'jumlah'        => 'required|numeric|min:1',
            'tanggal'       => 'required|date',
            'keterangan'    => 'nullable|string',
        ]);

        $alokasi->update($request->all());

        return back()->with('success', 'Alokasi berhasil diperbarui');
    }

    public function destroy($id)
    {
        AlokasiDonasi::findOrFail($id)->delete();
        return back()->with('success', 'Alokasi berhasil dihapus');
    }
}
