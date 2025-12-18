<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfiladminController extends Controller
{
    /**
     * Tampilkan halaman profil admin
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'User tidak ditemukan');
        }

        return view('admin.profile', compact('user'));
    }

    /**
     * Update profil admin
     */
    public function update(Request $request)
    {
        $user = User::find(Auth::id());

        if (!$user) {
            abort(403, 'User tidak ditemukan');
        }

        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email',
            'phone'  => 'nullable|string|max:20',
            'gender' => 'nullable|in:Laki-laki,Perempuan',
            'image'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'email', 'phone', 'gender']);

        // Proses upload gambar baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }

            // Simpan file ke storage/app/public/profile
            $data['image'] = $request->file('image')->store('profile', 'public');
        }

        // Update data user
        $user->fill($data);
        $user->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Logout admin
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda berhasil logout.');
    }
}
