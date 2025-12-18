<?php

namespace App\Http\Controllers;

use App\Helpers\EnumHelper;
use App\Models\Donasi;
use App\Models\KontakInformasi;
use App\Models\Program;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Traits\LogActivity;

class ProgramController extends Controller
{
    use LogActivity;
    private function normalizeDate($value)
    {
        if (! $value) {
            return null;
        }

        $normalized = null;

        // pattern => parser callback (mengembalikan Carbon instance atau string tanggal)
        $parsers = [
            '/^\d{2}\/\d{2}\/\d{4}$/'    => function ($v) { return Carbon::createFromFormat('d/m/Y', $v); }, // dd/mm/yyyy
            '/^\d{2}-[A-Za-z]{3}-\d{4}$/' => function ($v) { return Carbon::parse($v); },                // dd-MMM-yyyy
            '/^\d{4}-\d{2}-\d{2}$/'       => function ($v) { return Carbon::createFromFormat('Y-m-d', $v); } // yyyy-mm-dd
        ];

        foreach ($parsers as $pattern => $parser) {
            if (preg_match($pattern, $value)) {
                try {
                    $res = $parser($value);
                    // Jika parser mengembalikan Carbon / DateTime-like, format ke Y-m-d
                    if ($res instanceof \DateTimeInterface) {
                        $normalized = $res->format('Y-m-d');
                    } elseif (is_string($res) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $res)) {
                        $normalized = $res;
                    }
                } catch (\Exception $e) {
                    $normalized = null;
                }
                break;
            }
        }

        // Fallback: coba parse otomatis dengan Carbon
        if ($normalized === null) {
            try {
                $normalized = Carbon::parse($value)->format('Y-m-d');
            } catch (\Exception $e) {
                $normalized = null;
            }
        }

        return $normalized;
    }

    // LIST PROGRAM
    public function index(Request $request)
    {
        $this->logActivity($request, 'Melihat daftar program');
        $search = $request->search;

        // Ambil request range tanggal
        $start = $request->start_date;
        $end   = $request->end_date;

        $data = Program::query()

            // SEARCH
            ->when($search, function ($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                ->orWhere('kategori', 'like', "%$search%");
            })

            // FILTER RANGE TANGGAL
            ->when($start && $end, function ($q) use ($start, $end) {
                $q->whereBetween('created_at', [
                    Carbon::parse($start)->startOfDay(),
                    Carbon::parse($end)->endOfDay()
                ]);
            })

            ->orderBy('id', 'desc')
            ->paginate(10);
        return view('admin.program.index', compact('data', 'search'));
    }

    // HALAMAN CREATE
    public function create(Request $request)
    {
        $this->logActivity($request, 'Membuka form tambah program');
        $kategori = EnumHelper::getEnumValues('programs', 'kategori');
        $mode = 'create';

        return view('admin.program.form', compact('kategori', 'mode'));
    }

    public function show(Request $request, $id)
    {
        $this->logActivity($request, 'Melihat detail program', [
            'program_id' => $id
        ]);

        $program = Program::findOrFail($id);

        return view('admin.program.form', [
            'program' => $program,
            'mode'    => 'show'
        ]);
    }

    // SIMPAN PROGRAM
    public function store(Request $request)
    {
        $this->logActivity($request, 'Menyimpan program baru');
        // Validasi dasar (tanpa closure untuk tanggal)
        $request->validate([
            'kategori'      => 'required|string',
            'judul'         => 'required|string|max:255',
            'deskripsi'     => 'nullable|string',
            'foto'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'target_waktu'  => 'nullable|string',
            'target_dana'   => 'nullable|numeric|min:0',
            'min_donasi'    => 'nullable|numeric|min:0',
            'custom_nominal'=> 'nullable|array',
            'open_goals'    => 'nullable|boolean'
        ]);

        // NORMALISASI + VALIDASI LOGIKA TANGGAL (setelah validate)
        if ($request->boolean('open_goals')) {
            $targetWaktu = null;
        } else {
            $targetWaktu = $this->normalizeDate($request->target_waktu);

            if ($request->target_waktu && $targetWaktu === null) {
                return back()->withInput()->with('error', 'Format tanggal target waktu tidak valid. Gunakan DD/MM/YYYY.');
            }

            if ($targetWaktu !== null) {
                $date = Carbon::parse($targetWaktu);
                if ($date->lessThanOrEqualTo(Carbon::today())) {
                    return back()->withInput()->with('error', 'Target waktu harus lebih dari hari ini.');
                }
            }
        }

        // Upload Foto
        $fotoPath = $request->hasFile('foto')
            ? $request->file('foto')->store('programs', 'public')
            : null;

        $program = Program::create([
            'kategori'       => $request->kategori,
            'judul'          => $request->judul,
            'deskripsi'      => $request->deskripsi,
            'foto'           => $fotoPath,
            'target_waktu'   => $targetWaktu,
            'target_dana'    => $request->target_dana,
            'open_goals'     => $request->boolean('open_goals'),
            'min_donasi'     => $request->min_donasi ?? 0,
            'custom_nominal' => $request->custom_nominal ?? []
        ]);
        $this->logActivity($request, 'Program berhasil dibuat', [
            'program_id' => $program->id
        ]);

        return redirect()
            ->route('admin.program.show', $program->id)
            ->with('success', 'Program berhasil dibuat!');
    }

    // HALAMAN EDIT
    public function edit(Request $request, Program $program)
    {
         $this->logActivity($request, 'Membuka form edit program', [
            'program_id' => $program->id
        ]);
        $kategori = EnumHelper::getEnumValues('programs', 'kategori');

        $program->foto_url = $program->foto ? asset('storage/'.$program->foto) : null;

        $mode = 'edit';

        return view('admin.program.form', compact('kategori', 'mode', 'program'));
    }

    // UPDATE PROGRAM
    public function update(Request $request, Program $program)
    {
        $this->logActivity($request, 'Update program', [
            'program_id' => $program->id
        ]);
        // Validasi dasar
        $request->validate([
            'kategori'      => 'required|string',
            'judul'         => 'required|string|max:255',
            'deskripsi'     => 'nullable|string',
            'foto'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'target_waktu'  => 'nullable|string',
            'target_dana'   => 'nullable|numeric|min:0',
            'min_donasi'    => 'nullable|numeric|min:0',
            'custom_nominal'=> 'nullable|array',
            'open_goals'    => 'nullable|boolean'
        ]);

        // Foto baru?
        $fotoPath = $program->foto;
        if ($request->hasFile('foto')) {
            if ($program->foto && Storage::disk('public')->exists($program->foto)) {
                Storage::disk('public')->delete($program->foto);
            }
            $fotoPath = $request->file('foto')->store('programs', 'public');
        }

        // NORMALISASI + VALIDASI TANGGAL
        if ($request->boolean('open_goals')) {
            $targetWaktu = null;
        } else {
            $targetWaktu = $this->normalizeDate($request->target_waktu);

            if ($request->target_waktu && $targetWaktu === null) {
                return back()->withInput()->with('error', 'Format tanggal target waktu tidak valid. Gunakan DD/MM/YYYY.');
            }

            if ($targetWaktu !== null) {
                $date = Carbon::parse($targetWaktu);
                if ($date->lessThanOrEqualTo(Carbon::today())) {
                    return back()->withInput()->with('error', 'Target waktu harus lebih dari hari ini.');
                }
            }
        }

        $program->update([
            'kategori'       => $request->kategori,
            'judul'          => $request->judul,
            'deskripsi'      => $request->deskripsi,
            'foto'           => $fotoPath,
            'target_waktu'   => $targetWaktu,
            'target_dana'    => $request->target_dana,
            'open_goals'     => $request->boolean('open_goals'),
            'min_donasi'     => $request->min_donasi,
            'custom_nominal' => $request->custom_nominal ?? []
        ]);

        return redirect()->route('program.index')->with('success','Program diperbarui');
    }

    // DELETE PROGRAM
    public function destroy(Program $program)
    {
        $this->logActivity(request(), 'Menghapus program', [
            'program_id' => $program->id
        ]);
        if ($program->foto && Storage::disk('public')->exists($program->foto)) {
            Storage::disk('public')->delete($program->foto);
        }

        $program->delete();

        return redirect()->route('program.index')->with('success','Program dihapus');
    }
   public function byKategori($kategori)
    {
        $this->logActivity(request(), 'Melihat program berdasarkan kategori', [
            'kategori' => $kategori
        ]);
        // Normalisasi kategori (zakat → zakat)
        $kategoriNormalized = strtolower($kategori);

        // Ambil data program sesuai kategori tanpa case sensitive
        $data = Program::whereRaw('LOWER(kategori) = ?', [$kategoriNormalized])
            ->withSum(['donasis as terkumpul' => function ($q) {
                $q->where('status', 'paid');
            }], 'nominal')
            ->withCount(['donasis as jumlah_donasi' => function ($q) {
                $q->where('status', 'paid');
            }])
            ->latest()
            ->paginate(9);

        // TETAP TAMPIL meskipun $data kosong
        return view('program.donasi.index', [
            'kategori' => ucfirst($kategoriNormalized),
            'data'     => $data
        ]);
    }

    public function detail($kategori, $slug)
    {
        $item = Program::where('slug', $slug)
            ->withSum([
                'donasis as terkumpul' => fn ($q) => $q->where('status', 'paid')
            ], 'nominal')
            ->withCount([
                'donasis as jumlahDonasi' => fn ($q) => $q->where('status', 'paid')
            ])
            ->firstOrFail();

        $terkumpul = $item->terkumpul ?? 0;
        $target    = $item->target_dana ?? 1;
        $persen    = min(100, ($terkumpul / $target) * 100);

        // SISA HARI
        if ($item->open_goals || !$item->target_waktu) {
            $sisaHari = 'Tanpa batas waktu';
        } else {
            $sisaHari = now()->diffInDays($item->target_waktu, false);
            $sisaHari = $sisaHari > 0
                ? $sisaHari . ' hari lagi'
                : 'Berakhir';
        }

        $donaturs = Donasi::where('program_id', $item->id)
            ->where('status', 'paid')
            ->latest()
            ->get();

        return view('program.donasi.detail.index', [
            'item'          => $item,
            'donaturs'      => $donaturs,
            'terkumpul'     => $terkumpul,
            'target'        => $target,
            'persen'        => $persen,
            'jumlahDonasi'  => $item->jumlahDonasi,
            'sisaHari'      => $sisaHari,
        ]);
    }
}
