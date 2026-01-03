@extends('admin.components.app')

@section('title', 'Edit Kontak Donasi Offline')

@section('content')

<!-- ================= HEADER & BREADCRUMB ================= -->
<div class="mb-6 flex items-start justify-between">
    <h1 class="text-2xl font-bold text-green-700">
        Edit Kontak Donasi Offline
    </h1>

    <nav class="text-sm text-gray-600">
        <a href="{{ route('admin.dashboard') }}" class="hover:underline">Dashboard</a>
        <span class="mx-1">›</span>
        <a href="{{ route('admin.contactdonasioffline.index') }}" class="hover:underline">
            Kontak Donasi Offline
        </a>
        <span class="mx-1">›</span>
        <span class="font-semibold text-gray-800">Edit Data</span>
    </nav>
</div>
<!-- ================= STAT CARDS ================= -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-green-600">
        <p class="text-sm text-gray-500">Total Diterima</p>
        <h2 class="text-xl font-bold text-green-700">
            Rp {{ number_format($totalDiterima, 0, ',', '.') }}
        </h2>
    </div>

    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-blue-600">
        <p class="text-sm text-gray-500">Total Prospek</p>
        <h2 class="text-xl font-bold text-blue-700">
            Rp {{ number_format($totalProspek, 0, ',', '.') }}
        </h2>
    </div>

    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-red-600">
        <p class="text-sm text-gray-500">Total Gagal</p>
        <h2 class="text-xl font-bold text-red-700">
            Rp {{ number_format($totalGagal, 0, ',', '.') }}
        </h2>
    </div>

    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-purple-600">
        <p class="text-sm text-gray-500">Rasio Donasi</p>
        <h2 class="text-xl font-bold
            {{ $rasio >= 70 ? 'text-green-700' :
               ($rasio >= 40 ? 'text-yellow-600' :
               'text-red-600') }}">
            {{ number_format($rasio, 1) }}%
        </h2>
    </div>

</div>

<!-- ================= FORM + PREVIEW ================= -->
<div
    x-data="{
        name: @js($contact->name),
        email: @js($contact->email),
        phone: @js($contact->phone),
        gender: @js($contact->gender),
        country: @js($contact->country),
        province: @js($contact->province),
        city: @js($contact->city),
        address: @js($contact->address)
    }"
    class="grid grid-cols-1 xl:grid-cols-3 gap-6"
>

    <!-- ================= FORM ================= -->
    <div class="xl:col-span-2 bg-white rounded-xl shadow p-6">
        <form method="POST"
              action="{{ route('admin.contactdonasioffline.update', $contact->id) }}"
              class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @csrf
            @method('PUT')

            <!-- NAMA -->
            <div class="md:col-span-2">
                <label for="" class="text-sm font-medium">Nama *</label>
                <input type="text" name="name" x-model="name" required
                       class="w-full rounded-lg border border-gray-300 p-2
                              focus:border-green-500 focus:ring-1 focus:ring-green-500">
            </div>

            <!-- EMAIL -->
            <div>
                <label for="" class="text-sm font-medium">Email *</label>
                <input type="email" name="email" x-model="email" required
                       class="w-full rounded-lg border border-gray-300 p-2
                              focus:border-green-500 focus:ring-1 focus:ring-green-500">
            </div>

            <!-- TELEPON -->
            <div>
                <label for="" class="text-sm font-medium">Nomor Telepon *</label>
                <input type="text" name="phone" x-model="phone" required
                       class="w-full rounded-lg border border-gray-300 p-2
                              focus:border-green-500 focus:ring-1 focus:ring-green-500">
            </div>

            <!-- GENDER -->
            <div>
                <label for="" class="text-sm font-medium">Gender *</label>
                <select name="gender" x-model="gender" required
                        class="w-full rounded-lg border border-gray-300 p-2
                               focus:border-green-500 focus:ring-1 focus:ring-green-500">
                    <option value="">-- Pilih --</option>
                    <option value="male">Laki-laki</option>
                    <option value="female">Perempuan</option>
                </select>
            </div>

            <!-- COUNTRY -->
            <div>
                <label for="" class="text-sm font-medium">Negara</label>
                <input type="text" name="country" x-model="country"
                       class="w-full rounded-lg border border-gray-300 p-2">
            </div>

            <!-- PROVINCE -->
            <div>
                <label for="" class="text-sm font-medium">Provinsi</label>
                <input type="text" name="province" x-model="province"
                       class="w-full rounded-lg border border-gray-300 p-2">
            </div>

            <!-- CITY -->
            <div>
                <label for="" class="text-sm font-medium">Kota</label>
                <input type="text" name="city" x-model="city"
                       class="w-full rounded-lg border border-gray-300 p-2">
            </div>

            <!-- ADDRESS -->
            <div class="md:col-span-2">
                <label for="" class="text-sm font-medium">Alamat</label>
                <textarea name="address" x-model="address" rows="3"
                          class="w-full rounded-lg border border-gray-300 p-2"></textarea>
            </div>

            <!-- ACTION -->
            <div class="md:col-span-2 flex justify-end gap-2 pt-4">
                <a href="{{ route('admin.contactdonasioffline.index') }}"
                   class="px-4 py-2 rounded-lg border text-sm hover:bg-gray-100">
                    Batal
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700">
                    Update
                </button>
            </div>
        </form>
    </div>

    <!-- ================= PREVIEW ================= -->
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="font-semibold text-gray-700 mb-4">
            Preview Kontak
        </h3>

        <div class="space-y-3 text-sm">
            <p><b>Nama:</b> <span x-text="name || '-'"></span></p>
            <p><b>Email:</b> <span x-text="email || '-'"></span></p>
            <p><b>Telepon:</b> <span x-text="phone || '-'"></span></p>
            <p><b>Gender:</b> <span x-text="gender || '-'"></span></p>
            <p><b>Negara:</b> <span x-text="country || '-'"></span></p>
            <p><b>Provinsi:</b> <span x-text="province || '-'"></span></p>
            <p><b>Kota:</b> <span x-text="city || '-'"></span></p>
            <p><b>Alamat:</b> <span x-text="address || '-'"></span></p>
        </div>
    </div>

</div>
@endsection
