<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TinyMceController extends Controller
{
    public function upload(Request $request)
    {
        // Validasi file upload dari TinyMCE
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg|max:4096',
        ], [
            'file.required' => 'Gambar wajib diupload.',
            'file.image'    => 'File harus berupa gambar.',
            'file.mimes'    => 'Format harus jpeg, jpg, atau png.',
            'file.max'      => 'Ukuran gambar maksimal 4MB.',
        ]);

        // Simpan ke storage/app/public/tinymce
        $path = $request->file('file')->store('tinymce', 'public');

        // Return URL ke TinyMCE
        return response()->json([
            'location' => asset('storage/' . $path)
        ]);
    }
}
