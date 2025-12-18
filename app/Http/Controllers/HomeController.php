<?php

namespace App\Http\Controllers;

use App\Models\Beritadankegiatan;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\KontakInformasi;
use App\Traits\LogActivity;

class HomeController extends Controller
{
    use LogActivity;
    /* ===============================
     | HOMEPAGE
     =============================== */
    public function index(Request $request)
    {
        $this->logActivity($request, 'Akses Homepage');

        $berita   = Beritadankegiatan::latest()->take(6)->get();
        $programs = Program::latest()->get();

        $kontak = KontakInformasi::first();

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
    public function profile(Request $request)
    {
        $this->logActivity($request, 'Lihat Profil');

        return view('pages.profile.show', [
            'user' => Auth::user(),
        ]);
    }

    /* ===============================
     | EDIT PROFILE
     =============================== */
    public function editProfile(Request $request)
    {
        $this->logActivity($request, 'Buka Form Edit Profil');

        return view('pages.profile.edit', [
            'user' => Auth::user(),
        ]);
    }


    /* ===============================
     | UPDATE PROFILE
     =============================== */
    public function updateProfile(Request $request)
    {
        $this->logActivity($request, 'Update Profil');

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
