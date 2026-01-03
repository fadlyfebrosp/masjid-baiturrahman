@extends('admin.components.app')

@section('title', 'Detail Kontak Donasi Offline')

@section('content')

<!-- ================= HEADER & BREADCRUMB ================= -->
<div class="mb-6 flex items-start justify-between">
    <h1 class="text-2xl font-bold text-green-700">
        Detail Kontak Donasi Offline
    </h1>

    <nav class="text-sm text-gray-600">
        <a href="{{ route('admin.dashboard') }}" class="hover:underline">Dashboard</a>
        <span class="mx-1">›</span>
        <a href="{{ route('admin.contactdonasioffline.index') }}" class="hover:underline">
            Kontak Donasi Offline
        </a>
        <span class="mx-1">›</span>
        <span class="font-semibold text-gray-800">Detail</span>
    </nav>
</div>

<!-- ================= STAT CARDS ================= -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

    {{-- TOTAL DITERIMA --}}
    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-green-600">
        <p class="text-sm text-gray-500">Total Diterima</p>
        <h2 class="text-xl font-bold text-green-700">
            Rp {{ number_format($totalDiterima, 0, ',', '.') }}
        </h2>
    </div>

    {{-- TOTAL PROSPEK --}}
    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-blue-600">
        <p class="text-sm text-gray-500">Total Prospek</p>
        <h2 class="text-xl font-bold text-blue-700">
            Rp {{ number_format($totalProspek, 0, ',', '.') }}
        </h2>
    </div>

    {{-- TOTAL GAGAL --}}
    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-red-600">
        <p class="text-sm text-gray-500">Total Gagal</p>
        <h2 class="text-xl font-bold text-red-700">
            Rp {{ number_format($totalGagal, 0, ',', '.') }}
        </h2>
    </div>

    {{-- RASIO --}}
    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-purple-600">
        <p class="text-sm text-gray-500">Rasio Donasi</p>
        <h2 class="text-xl font-bold text-purple-700">
            {{ number_format($rasio, 1) }}%
        </h2>
    </div>

</div>
<!-- ================= DETAIL + PREVIEW ================= -->
<div
    x-data="{
        name: @js($contact->name),
        email: @js($contact->email),
        phone: @js($contact->phone),
        gender: @js($contact->gender),
        country: @js($contact->country),
        province: @js($contact->province),
        city: @js($contact->city),
        address: @js($contact->address),
    }"
    class="grid grid-cols-1 xl:grid-cols-3 gap-6"
>

    <!-- ================= READ ONLY FORM ================= -->
    <div class="xl:col-span-2 bg-white rounded-xl shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <!-- NAMA -->
            <div class="md:col-span-2">
                <label for="" class="text-sm font-medium">Nama</label>
                <input type="text" x-model="name" disabled
                       class="w-full rounded-lg border border-gray-200 p-2 bg-gray-100">
            </div>

            <!-- EMAIL -->
            <div>
                <label for="" class="text-sm font-medium">Email</label>
                <input type="text" x-model="email" disabled
                       class="w-full rounded-lg border border-gray-200 p-2 bg-gray-100">
            </div>

            <!-- TELEPON -->
            <div>
                <label for="" class="text-sm font-medium">Nomor Telepon</label>
                <input type="text" x-model="phone" disabled
                       class="w-full rounded-lg border border-gray-200 p-2 bg-gray-100">
            </div>

            <!-- GENDER -->
            <div>
                <label for="" class="text-sm font-medium">Gender</label>
                <input type="text"
                       :value="gender === 'male' ? 'Laki-laki' : 'Perempuan'"
                       disabled
                       class="w-full rounded-lg border border-gray-200 p-2 bg-gray-100">
            </div>

            <!-- COUNTRY -->
            <div>
                <label for="" class="text-sm font-medium">Negara</label>
                <input type="text" x-model="country" disabled
                       class="w-full rounded-lg border border-gray-200 p-2 bg-gray-100">
            </div>

            <!-- PROVINCE -->
            <div>
                <label for="" class="text-sm font-medium">Provinsi</label>
                <input type="text" x-model="province" disabled
                       class="w-full rounded-lg border border-gray-200 p-2 bg-gray-100">
            </div>

            <!-- CITY -->
            <div>
                <label for="" class="text-sm font-medium">Kota</label>
                <input type="text" x-model="city" disabled
                       class="w-full rounded-lg border border-gray-200 p-2 bg-gray-100">
            </div>

            <!-- ADDRESS -->
            <div class="md:col-span-2">
                <label for="s" class="text-sm font-medium">Alamat</label>
                <textarea rows="3" x-model="address" disabled
                          class="w-full rounded-lg border border-gray-200 p-2 bg-gray-100"></textarea>
            </div>

            <!-- ACTION -->
            <div class="md:col-span-2 flex justify-end gap-2 pt-4">
                <a href="{{ route('admin.contactdonasioffline.index') }}"
                   class="px-4 py-2 rounded-lg border text-sm hover:bg-gray-100">
                    Kembali
                </a>

                <a href="{{ route('admin.contactdonasioffline.edit', $contact->id) }}"
                   class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700">
                    Edit
                </a>
            </div>

        </div>
    </div>

    <!-- ================= PREVIEW ================= -->
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="font-semibold text-gray-700 mb-4">
            Preview Kontak
        </h3>

        <div class="space-y-3 text-sm">
            <div>
                <p class="text-gray-500">Nama</p>
                <p class="font-medium" x-text="name || '-'"></p>
            </div>
            <div>
                <p class="text-gray-500">Email</p>
                <p class="font-medium" x-text="email || '-'"></p>
            </div>
            <div>
                <p class="text-gray-500">Telepon</p>
                <p class="font-medium" x-text="phone || '-'"></p>
            </div>
            <div>
                <p class="text-gray-500">Gender</p>
                <p class="font-medium"
                   x-text="gender === 'male' ? 'Laki-laki' : 'Perempuan'"></p>
            </div>
            <div>
                <p class="text-gray-500">Negara</p>
                <p class="font-medium" x-text="country || '-'"></p>
            </div>
            <div>
                <p class="text-gray-500">Provinsi</p>
                <p class="font-medium" x-text="province || '-'"></p>
            </div>
            <div>
                <p class="text-gray-500">Kota</p>
                <p class="font-medium" x-text="city || '-'"></p>
            </div>
            <div>
                <p class="text-gray-500">Alamat</p>
                <p class="font-medium" x-text="address || '-'"></p>
            </div>
        </div>
    </div>

</div>
@endsection
