<?php

namespace App\Http\Controllers;

use App\Models\Contactdonasioffline;
use App\Models\DonasiOffline;
use App\Models\User;
use Illuminate\Http\Request;

class ContactDonasiOfflineController extends Controller
{
    // ================= INDEX =================
    public function index()
    {
        $contacts = Contactdonasioffline::latest()->paginate(10);

        $totalContacts  = Contactdonasioffline::count();
        $totalUsers = User::whereNotIn('role', ['admin', 'finance'])->count();
        $totalAkunDanKelolaan = $totalUsers + $totalContacts;
        $totalRevenue = DonasiOffline::where('status', 'SELESAI')
            ->sum('nominal');

        // total order = donasi yang benar-benar terjadi
        $totalOrder = DonasiOffline::where('status', 'SELESAI')->count();

        // ORDER RATE
        $orderRate = $totalContacts > 0
            ? $totalOrder / $totalContacts
            : 0;

        // TOTAL KONTAK YANG PERNAH DONASI
        $totalDonaturAktif = DonasiOffline::where('status', 'SELESAI')
            ->distinct('contactdonasioffline_id')
            ->count('contactdonasioffline_id');

        // AVERAGE REVENUE PER DONATUR
        $averageRevenue = $totalDonaturAktif > 0
            ? $totalRevenue / $totalDonaturAktif
            : 0;

        return view('admin.contactdonasioffline.index', compact(
            'contacts',
            'totalContacts',
            'totalUsers',
            'totalAkunDanKelolaan',
            'orderRate',
            'averageRevenue',
            'totalRevenue'
        ));
    }

    // ================= CREATE =================
    public function create()
    {
        return view('admin.contactdonasioffline.tambah');
    }

    // ================= STORE =================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255',
            'phone'    => 'required|string|max:20',
            'gender'   => 'required|in:male,female',
            'country'  => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'city'     => 'nullable|string|max:100',
            'address'  => 'nullable|string',
        ]);

        Contactdonasioffline::create($validated);

        return redirect()
            ->route('admin.contactdonasioffline.index')
            ->with('success', 'Kontak donasi offline berhasil ditambahkan');
    }

    // ================= EDIT =================
    public function edit(Contactdonasioffline $contactdonasioffline)
    {
        $contactId = $contactdonasioffline->id;

        // TOTAL UANG DITERIMA
        $totalDiterima = DonasiOffline::where('contactdonasioffline_id', $contactId)
            ->where('status', 'SELESAI')
            ->sum('nominal');

        // TOTAL UANG PROSPEK
        $totalProspek = DonasiOffline::where('contactdonasioffline_id', $contactId)
            ->whereIn('status', ['PROSES', 'PENDING'])
            ->sum('nominal');

        // TOTAL UANG GAGAL
        $totalGagal = DonasiOffline::where('contactdonasioffline_id', $contactId)
            ->whereIn('status', ['GAGAL', 'BATAL'])
            ->sum('nominal');

        // TOTAL DONASI
        $totalDonasi = DonasiOffline::where('contactdonasioffline_id', $contactId)
            ->count();

        // TOTAL DONASI SELESAI
        $totalDonasiSelesai = DonasiOffline::where('contactdonasioffline_id', $contactId)
            ->where('status', 'SELESAI')
            ->count();

        // RASIO (%)
        $rasio = $totalDonasi > 0
            ? ($totalDonasiSelesai / $totalDonasi) * 100
            : 0;

        return view('admin.contactdonasioffline.edit', [
            'contact'        => $contactdonasioffline,
            'totalDiterima'  => $totalDiterima,
            'totalProspek'   => $totalProspek,
            'totalGagal'     => $totalGagal,
            'rasio'          => $rasio,
        ]);
    }
    public function update(Request $request, Contactdonasioffline $contactdonasioffline)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255',
            'phone'    => 'required|string|max:20',
            'gender'   => 'required|in:male,female',
            'country'  => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'city'     => 'nullable|string|max:100',
            'address'  => 'nullable|string',
        ]);

        $contactdonasioffline->update($validated);

        return redirect()
            ->route('admin.contactdonasioffline.index')
            ->with('success', 'Kontak donasi offline berhasil diperbarui');
    }
    public function show(Contactdonasioffline $contactdonasioffline)
    {
        $contactId = $contactdonasioffline->id;

        // TOTAL UANG DITERIMA
        $totalDiterima = DonasiOffline::where('contactdonasioffline_id', $contactId)
            ->where('status', 'SELESAI')
            ->sum('nominal');

        // TOTAL UANG PROSPEK
        $totalProspek = DonasiOffline::where('contactdonasioffline_id', $contactId)
            ->whereIn('status', ['PROSES', 'PENDING'])
            ->sum('nominal');

        // TOTAL UANG GAGAL
        $totalGagal = DonasiOffline::where('contactdonasioffline_id', $contactId)
            ->where('status', 'GAGAL')
            ->sum('nominal');

        // TOTAL DONASI (SEMUA STATUS)
        $totalDonasi = DonasiOffline::where('contactdonasioffline_id', $contactId)
            ->count();

        // TOTAL DONASI SELESAI
        $totalDonasiSelesai = DonasiOffline::where('contactdonasioffline_id', $contactId)
            ->where('status', 'SELESAI')
            ->count();

        // RASIO (%)
        $rasio = $totalDonasi > 0
            ? ($totalDonasiSelesai / $totalDonasi) * 100
            : 0;

        return view('admin.contactdonasioffline.detail', [
            'contact'        => $contactdonasioffline,
            'totalDiterima'  => $totalDiterima,
            'totalProspek'   => $totalProspek,
            'totalGagal'     => $totalGagal,
            'rasio'          => $rasio,
        ]);
    }
    public function destroy(Contactdonasioffline $contactdonasioffline)
    {
        $contactdonasioffline->delete();

        return redirect()
            ->route('admin.contactdonasioffline.index')
            ->with('success', 'Kontak donasi offline berhasil dihapus');
    }
    public function syncFromUsers()
    {
        $users = User::whereNotIn('role', ['admin', 'finance'])->get();

        foreach ($users as $user) {
            Contactdonasioffline::updateOrCreate(
                [
                    'user_id' => $user->id
                ],
                [
                    'name'   => $user->name,
                    'email'  => $user->email,
                    'phone'  => $user->phone,
                    'gender' => $user->gender,
                ]
            );
        }

        return redirect()
            ->route('admin.contactdonasioffline.index')
            ->with('success', 'Data user berhasil disinkronkan ke kontak donasi');
    }
}
