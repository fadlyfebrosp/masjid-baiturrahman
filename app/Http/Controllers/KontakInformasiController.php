<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\KontakInformasi;
use Illuminate\Http\Request;

class KontakInformasiController extends Controller
{
    public function index()
    {
        $kontak = KontakInformasi::first();

        return view('admin.pengaturan.general', compact('kontak'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_aplikasi'  => 'required|string|max:255',
            'alamat_lengkap' => 'required|string',
            'email'          => 'nullable|email|max:255',
            'nomor_telepon'  => 'nullable|string|max:20',
            'nomor_whatsapp' => 'required|string|max:20',
            'logo'           => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
        ]);

        $kontak = KontakInformasi::first();

        $data = $request->only([
            'nama_aplikasi',
            'alamat_lengkap',
            'email',
            'nomor_telepon',
            'nomor_whatsapp',
        ]);

        // ✅ Upload logo
        if ($request->hasFile('logo')) {

            // hapus logo lama
            if ($kontak && $kontak->logo && Storage::disk('public')->exists($kontak->logo)) {
                Storage::disk('public')->delete($kontak->logo);
            }

            $data['logo'] = $request->file('logo')
                ->store('logo-aplikasi', 'public');
        }

        if ($kontak) {
            $kontak->update($data);
        } else {
            KontakInformasi::create($data);
        }

        return back()->with('success', 'Identitas aplikasi berhasil diperbarui');
    }
}
