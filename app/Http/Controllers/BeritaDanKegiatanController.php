<?php

namespace App\Http\Controllers;

use App\Models\BeritaDanKegiatan;
use App\Models\KontakInformasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BeritaDanKegiatanController extends Controller
{
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
        $search = $request->search;

        $data = BeritaDanKegiatan::when($search, function ($query) use ($search) {
            $query->where('judul', 'like', "%$search%");
        })->orderBy('id', 'DESC')->get();
        $logo = $this->getLogo();
        return view('admin.beritadankegiatan.index', compact('data', 'search','logo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'namamasjid' => 'required',
            'tanggal' => 'required',
            'kategori' => 'required',
            'deskripsi' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $foto = null;

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('berita-foto', 'public');
        }

        BeritaDanKegiatan::create([
            'judul' => $request->judul,
            'namamasjid' => $request->namamasjid,
            'tanggal' => $request->tanggal,
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,
            'foto' => $foto,
        ]);

        return back()->with('success', 'Berhasil menambahkan data!');
    }

    public function update(Request $request, $id)
    {
        $data = BeritaDanKegiatan::findOrFail($id);

        $request->validate([
            'judul' => 'required',
            'namamasjid' => 'required',
            'tanggal' => 'required',
            'kategori' => 'required',
            'deskripsi' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $foto = $data->foto;

        if ($request->hasFile('foto')) {
            if ($foto && Storage::disk('public')->exists($foto)) {
                Storage::disk('public')->delete($foto);
            }
            $foto = $request->file('foto')->store('berita-foto', 'public');
        }

        $data->update([
            'judul' => $request->judul,
            'namamasjid' => $request->namamasjid,
            'tanggal' => $request->tanggal,
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,
            'foto' => $foto,
        ]);

        return back()->with('success', 'Berhasil mengupdate data!');
    }

    public function destroy($id)
    {
        $data = BeritaDanKegiatan::findOrFail($id);

        if ($data->foto && Storage::disk('public')->exists($data->foto)) {
            Storage::disk('public')->delete($data->foto);
        }

        $data->delete();

        return back()->with('success', 'Berhasil menghapus data!');
    }
    public function showPublic()
    {
        $data = BeritaDankegiatan::latest()->paginate(9);
        $logo = $this->getLogo();
        return view('pages.berita', compact('data', 'logo'));
    }

    public function detail($judul)
    {
        $judul = urldecode($judul);

        $berita = BeritaDankegiatan::where('judul', $judul)->firstOrFail();
        $beritaLainnya = BeritaDankegiatan::where('id', '!=', $berita->id)
            ->latest()
            ->take(5)
            ->get();
        $logo = $this->getLogo();
        return view('pages.detail', compact('berita', 'beritaLainnya','logo'));
    }
}
