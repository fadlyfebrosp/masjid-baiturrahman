<?php

namespace App\Http\Controllers;

use App\Models\Beritadankegiatan;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\KontakInformasi;


class HomeController extends Controller
{
    /* ===============================
     | HOMEPAGE
     =============================== */
    public function index()
    {
        $berita   = Beritadankegiatan::latest()->take(6)->get();
        $programs = Program::latest()->get();

        // ✅ Ambil data kontak (1 baris saja)
        $kontak = KontakInformasi::first();

        // ✅ Siapkan logo aman (fallback kalau null)
        $logo = $kontak && $kontak->logo
            ? asset('storage/' . $kontak->logo)
            : asset('assets/img/logo1.png');

        return view('index', compact(
            'berita',
            'programs',
            'logo'
        ));
    }

    /* ===============================
     | SHOW PROFILE (READ ONLY)
     =============================== */
    public function profile()
    {
        return view('pages.profile.show', [
            'user' => Auth::user(),
        ]);
    }

    /* ===============================
     | EDIT PROFILE
     =============================== */
    public function editProfile()
    {
        return view('pages.profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    /* ===============================
     | UPDATE PROFILE
     =============================== */
    public function updateProfile(Request $request)
    {
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

        return redirect()
            ->route('profile')
            ->with('success', 'Profil berhasil diperbarui');
    }
}
