<?php

namespace App\Http\Controllers;

use App\Models\BeritaDanKegiatan;
use App\Models\BeritaFoto;
use App\Models\KontakInformasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class BeritaDanKegiatanController extends Controller
{
    private const DEFAULT_LOGO = 'assets/img/Image-not-found.png';
    private const STORAGE_PATH = 'storage/';

    private function getLogo(): string
    {
        $kontak = KontakInformasi::first();

        if ($kontak && $kontak->logo && Storage::disk('public')->exists($kontak->logo)) {
            return asset(self::STORAGE_PATH . $kontak->logo);
        }

        return asset(self::DEFAULT_LOGO);
    }

    /* =========================
     * INDEX
     * ========================= */
    public function index(Request $request)
    {
        $search = $request->search;

        $data = BeritaDanKegiatan::with('fotos')
            ->when($search, function ($q) use ($search) {
                $q->where('judul', 'like', "%$search%");
            })
            ->latest()
            ->get();

        $logo = $this->getLogo();

        return view('admin.beritadankegiatan.index', compact('data', 'search', 'logo'));
    }

    /* =========================
     * STORE (MULTI IMAGE)
     * ========================= */
    public function store(Request $request)
    {
        $request->validate([
            'judul'       => 'required',
            'namamasjid'  => 'required',
            'tanggal'     => 'required|date',
            'kategori'    => 'required',
            'deskripsi'   => 'nullable',
            'foto'        => 'nullable|array',
            'foto.*'      => 'nullable|image|max:4096',
        ]);

        DB::transaction(function () use ($request) {

            $berita = BeritaDanKegiatan::create([
                'judul'       => $request->judul,
                'namamasjid'  => $request->namamasjid,
                'tanggal'     => $request->tanggal,
                'kategori'    => $request->kategori,
                'deskripsi'   => $request->deskripsi,
            ]);

            if ($request->hasFile('foto')) {
                foreach ($request->file('foto') as $file) {

                    if (!$file->isValid()) {
                        continue;
                    }

                    $path = $file->store('berita', 'public');

                    BeritaFoto::create([
                        'berita_dan_kegiatan_id' => $berita->id,
                        'path' => $path,
                    ]);
                }
            }
        });

        return back()->with('success', 'Data berhasil disimpan');
    }
    /* =========================
     * UPDATE (TAMBAH FOTO BARU)
     * ========================= */
    public function update(Request $request, $id)
    {
        $berita = BeritaDanKegiatan::findOrFail($id);

        $berita->update([
            'judul'       => $request->judul,
            'namamasjid'  => $request->namamasjid,
            'tanggal'     => $request->tanggal,
            'kategori'    => $request->kategori,
            'deskripsi'   => $request->deskripsi,
        ]);

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {

                if (!$file->isValid()) {
                    continue;
                }

                $path = $file->store('berita', 'public');

                BeritaFoto::create([
                    'berita_dan_kegiatan_id' => $berita->id,
                    'path' => $path,
                ]);
            }
        }

        return back()->with('success', 'Data berhasil diperbarui');
    }

    /* =========================
     * DELETE BERITA + FOTO
     * ========================= */
    public function destroy($id)
    {
        $berita = BeritaDanKegiatan::with('fotos')->findOrFail($id);

        foreach ($berita->fotos as $foto) {
            if (Storage::disk('public')->exists($foto->path)) {
                Storage::disk('public')->delete($foto->path);
            }
        }

        $berita->delete();

        return back()->with('success', 'Berhasil menghapus data!');
    }
    public function destroyFoto($id)
    {
        $foto = BeritaFoto::findOrFail($id);

        if (Storage::disk('public')->exists($foto->path)) {
            Storage::disk('public')->delete($foto->path);
        }

        $foto->delete();

        return response()->json([
            'success' => true
        ]);
    }

    /* =========================
     * PUBLIC
     * ========================= */
    public function showPublic()
    {
        $data = BeritaDanKegiatan::with('fotos')
            ->orderBy('tanggal', 'desc')
            ->paginate(9);
        $logo = $this->getLogo();

        return view('pages.berita', compact('data', 'logo'));
    }

    public function detail($judul)
    {
        $judul = urldecode($judul);

        $berita = BeritaDanKegiatan::with('fotos')
            ->where('judul', $judul)
            ->firstOrFail();

        $beritaLainnya = BeritaDanKegiatan::where('id', '!=', $berita->id)
            ->latest()
            ->take(5)
            ->get();

        $logo = $this->getLogo();

        return view('pages.detail', compact('berita', 'beritaLainnya', 'logo'));
    }
}
