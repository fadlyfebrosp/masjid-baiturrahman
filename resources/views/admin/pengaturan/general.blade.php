@extends('admin.components.app')

@section('title', 'General Settings')

@section('content')

<!-- ================= HEADER ================= -->
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">General Settings</h1>
        <p class="text-sm text-gray-500">Identitas & informasi aplikasi</p>
    </div>

    <div class="text-sm text-gray-500">
        <a href="{{ route('admin.dashboard') }}" class="hover:underline">Dashboard</a>
        <span class="mx-1">›</span>
        <span class="font-semibold">Pengaturan</span>
        <span class="mx-1">›</span>
        <span class="font-semibold">General</span>
    </div>
</div>

<!-- ================= ALERT ================= -->
@if(session('success'))
<div class="mb-4 bg-green-100 text-green-800 px-4 py-3 rounded-lg">
    {{ session('success') }}
</div>
@endif

@if ($errors->any())
<div class="mb-4 bg-red-100 text-red-700 px-4 py-3 rounded-lg">
    <ul class="list-disc pl-5">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- ================= FORM ================= -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Edit Identitas Aplikasi</h2>

        <form
            action="{{ route('admin.pengaturan.general.update') }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-4"
        >
            @csrf
            @method('PUT')

            <!-- NAMA APLIKASI -->
            <div>
                <label for="" class="block text-sm font-medium mb-1">Nama Aplikasi</label>
                <input
                    type="text"
                    name="nama_aplikasi"
                    value="{{ old('nama_aplikasi', $kontak->nama_aplikasi ?? '') }}"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-500"
                >
            </div>

            <!-- ALAMAT -->
            <div>
                <label for="" class="block text-sm font-medium mb-1">Alamat Lengkap</label>
                <textarea
                    name="alamat_lengkap"
                    rows="3"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2
                        focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-500"
                >{{ old('alamat_lengkap', $kontak->alamat_lengkap ?? '') }}</textarea>
            </div>
            <!-- EMAIL -->
            <div>
                <label for="" class="block text-sm font-medium mb-1">Email</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email', $kontak->email ?? '') }}"
                    placeholder="admin@aplikasi.com"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2
                        focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-500"
                >
                <p class="text-xs text-gray-500 mt-1">Opsional</p>
            </div>

            <!-- TELEPON -->
            <div>
                <label for="" class="block text-sm font-medium mb-1">Nomor Telepon</label>
                    <input
                        type="text"
                        name="nomor_telepon"
                        value="{{ old('nomor_telepon', $kontak->nomor_telepon ?? '') }}"
                        placeholder="08xxxxxxxxxx"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2
                            focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-500"
                    >
                    <p class="text-xs text-gray-500 mt-1">Opsional</p>
            </div>

            <!-- WHATSAPP -->
            <div>
                <label for="" class="block text-sm font-medium mb-1">Nomor WhatsApp</label>
                <input
                    type="text"
                    name="nomor_whatsapp"
                    value="{{ old('nomor_whatsapp', $kontak->nomor_whatsapp ?? '') }}"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2
                        focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-500"
                >
            </div>

            <!-- LOGO -->
            <div>
                <label for="" class="block text-sm font-medium mb-1">Logo Aplikasi</label>
                <input
                    type="file"
                    name="logo"
                    accept="image/*"
                    class="w-full text-sm border border-gray-300 rounded-lg p-2"
                >
                <p class="text-xs text-gray-500 mt-1">
                    JPG / PNG / SVG • Max 2MB
                </p>
            </div>

            <button
                type="submit"
                class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-semibold"
            >
                Update
            </button>
        </form>
    </div>

    <!-- ================= PREVIEW ================= -->
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Preview</h2>

        <!-- LOGO PREVIEW -->
        <div class="flex justify-center mb-4">
            @if(!empty($kontak->logo))
                <img
                    src="{{ asset('storage/' . $kontak->logo) }}"
                    alt="Logo Aplikasi"
                    class="h-24 object-contain"
                >
            @else
                <div class="h-24 w-24 flex items-center justify-center bg-gray-100 text-gray-400 rounded">
                    No Logo
                </div>
            @endif
        </div>

        <div class="space-y-2 text-sm text-gray-700">
            <div class="flex">
                <span class="w-32 font-semibold">Nama Aplikasi</span>
                <span>: {{ $kontak->nama_aplikasi ?? '-' }}</span>
            </div>

            <div class="flex">
                <span class="w-32 font-semibold">Alamat</span>
                <span>: {{ $kontak->alamat_lengkap ?? '-' }}</span>
            </div>

            <div class="flex">
                <span class="w-32 font-semibold">Email</span>
                <span>: {{ $kontak->email ?? '-' }}</span>
            </div>

            <div class="flex">
                <span class="w-32 font-semibold">Telepon</span>
                <span>: {{ $kontak->nomor_telepon ?? '-' }}</span>
            </div>

            <div class="flex">
                <span class="w-32 font-semibold">WhatsApp</span>
                <span>: {{ $kontak->nomor_whatsapp ?? '-' }}</span>
            </div>
        </div>
    </div>

</div>
@endsection
