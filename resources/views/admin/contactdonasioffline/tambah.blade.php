@extends('admin.components.app')

@section('title', 'Tambah Kontak Donasi Offline')

@section('content')

<!-- ================= HEADER & BREADCRUMB ================= -->
<div class="mb-6 flex items-start justify-between">
    <h1 class="text-2xl font-bold text-green-700">
        Tambah Kontak Donasi Offline
    </h1>

    <nav class="text-sm text-gray-600">
        <a href="{{ route('admin.dashboard') }}" class="hover:underline">Dashboard</a>
        <span class="mx-1">›</span>
        <a href="{{ route('admin.contactdonasioffline.index') }}" class="hover:underline">
            Kontak Donasi Offline
        </a>
        <span class="mx-1">›</span>
        <span class="font-semibold text-gray-800">Tambah Data</span>
    </nav>
</div>

<!-- ================= STAT CARDS (DUMMY) ================= -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    @foreach ([
        ['Total Diterima','Rp0','green'],
        ['Total Prospek','Rp0','blue'],
        ['Total Gagal','Rp0','red'],
        ['Rasio','0%','purple'],
    ] as [$label,$value,$color])
        <div class="bg-white rounded-xl shadow p-5 border-l-4 border-{{ $color }}-600">
            <p class="text-sm text-gray-500">{{ $label }}</p>
            <h2 class="text-xl font-bold text-{{ $color }}-700">{{ $value }}</h2>
        </div>
    @endforeach
</div>

<!-- ================= FORM + PREVIEW ================= -->
<div
    x-data="{
        name:'',
        email:'',
        phone:'',
        gender:'',
        country:'',
        province:'',
        city:'',
        address:''
    }"
    class="grid grid-cols-1 xl:grid-cols-3 gap-6"
>

    <!-- ================= FORM ================= -->
    <div class="xl:col-span-2 bg-white rounded-xl shadow p-6">
        <form method="POST"
              action="{{ route('admin.contactdonasioffline.store') }}"
              class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @csrf

            <!-- NAMA -->
            <div class="md:col-span-2">
                <label class="text-sm font-medium">Nama *</label>
                <input type="text" name="name" x-model="name" required
                       class="w-full rounded-lg border border-gray-300 p-2
                              focus:border-green-500 focus:ring-1 focus:ring-green-500">
            </div>

            <!-- EMAIL -->
            <div>
                <label class="text-sm font-medium">Email *</label>
                <input type="email" name="email" x-model="email" required
                       class="w-full rounded-lg border border-gray-300 p-2
                              focus:border-green-500 focus:ring-1 focus:ring-green-500">
            </div>

            <!-- TELEPON -->
            <div>
                <label class="text-sm font-medium">Nomor Telepon *</label>
                <input type="text" name="phone" x-model="phone" required
                       class="w-full rounded-lg border border-gray-300 p-2
                              focus:border-green-500 focus:ring-1 focus:ring-green-500">
            </div>

            <!-- GENDER -->
            <div>
                <label class="text-sm font-medium">Gender *</label>
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
                <label class="text-sm font-medium">Negara</label>
                <input type="text" name="country" x-model="country"
                       class="w-full rounded-lg border border-gray-300 p-2
                              focus:border-green-500 focus:ring-1 focus:ring-green-500">
            </div>

            <!-- PROVINCE -->
            <div>
                <label class="text-sm font-medium">Provinsi</label>
                <input type="text" name="province" x-model="province"
                       class="w-full rounded-lg border border-gray-300 p-2
                              focus:border-green-500 focus:ring-1 focus:ring-green-500">
            </div>

            <!-- CITY -->
            <div>
                <label class="text-sm font-medium">Kota</label>
                <input type="text" name="city" x-model="city"
                       class="w-full rounded-lg border border-gray-300 p-2
                              focus:border-green-500 focus:ring-1 focus:ring-green-500">
            </div>

            <!-- ADDRESS -->
            <div class="md:col-span-2">
                <label class="text-sm font-medium">Alamat</label>
                <textarea name="address"
                          x-model="address"
                          rows="3"
                          class="w-full rounded-lg border border-gray-300 p-2
                                 focus:border-green-500 focus:ring-1 focus:ring-green-500"></textarea>
            </div>

            <!-- ACTION -->
            <div class="md:col-span-2 flex justify-end gap-2 pt-4">
                <a href="{{ route('admin.contactdonasioffline.index') }}"
                   class="px-4 py-2 rounded-lg border text-sm hover:bg-gray-100">
                    Batal
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700">
                    Simpan
                </button>
            </div>
        </form>
    </div>

    <!-- ================= LIVE PREVIEW ================= -->
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
                <p class="font-medium capitalize" x-text="gender || '-'"></p>
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
