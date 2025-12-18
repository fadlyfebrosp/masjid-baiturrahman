@extends('admin.components.app')

@section('title', 'Dashboard Admin')

@section('content')

<!-- Header + Breadcrumb -->
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-green-700">Dashboard</h1>

    <div class="text-sm text-gray-600">
        <a href="dashboard" class="hover:underline">Home</a>
        <span class="mx-1">></span>
        <span class="font-semibold text-gray-800">Dashboard</span>
    </div>
</div>

<!-- Banner Selamat Datang -->
<div class="flex items-center bg-green-600 text-white text-sm font-semibold px-4 py-3 rounded-lg shadow mb-8">
    <svg class="w-5 h-5 mr-3 opacity-90" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path d="M12.432 0c1.34 0 2.01.912 2.01 1.957 0 1.305-1.164 2.512-2.679 2.512-1.269 0-2.009-.75-1.974-1.99C9.789 1.436 10.67 0 12.432 0zM8.309 20c-1.058 0-1.833-.652-1.093-3.524l1.214-5.092c.211-.814.246-1.141 0-1.141-.317 0-1.689.562-2.502 1.117l-.528-.88c2.572-2.186 5.531-3.467 6.801-3.467 1.057 0 1.233 1.273.705 3.23l-1.391 5.352c-.246.945-.141 1.271.106 1.271.317 0 1.357-.392 2.379-1.207l.6.814C12.098 19.02 9.365 20 8.309 20z"/>
    </svg>

    <p class="text-base">
        Selamat datang <span class="font-bold">{{ Auth::user()->name }}</span>
        di panel admin <span class="font-bold">Masjid Baiturrahman</span>
    </p>
</div>

<!-- CARD STATISTIK -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6">

    <!-- Kelola Akun -->
    <div class="rounded-lg shadow bg-[#00b7ff] text-white p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-bold">{{ $totalAkun }}</h1>
                <p class="text-lg mt-1">Kelola Akun</p>
            </div>

            <svg class="w-20 h-20 opacity-30" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
        </div>

        <a href="{{ route('admin.account') }}" class="block mt-4 text-sm bg-[#009bd6] hover:bg-[#008cc1] py-2 text-center rounded-md">
            More info →
        </a>
    </div>

    <!-- Program Donasi -->
    <div class="rounded-lg shadow bg-green-600 text-white p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-bold">{{ $totalProgram }}</h1>
                <p class="text-lg mt-1">Program Donasi</p>
            </div>

            <svg class="w-20 h-20 opacity-30" fill="currentColor" viewBox="0 0 24 24">
                <path d="M21 3H3v18h18V3zM7 7h10v2H7V7zm0 4h10v2H7v-2zm0 4h7v2H7v-2z"/>
            </svg>
        </div>

        <a href="{{ route('admin.program.index') }}" class="block mt-4 text-sm bg-green-700 hover:bg-green-800 py-2 text-center rounded-md">
            More info →
        </a>
    </div>

    <!-- Berita & Kegiatan -->
    <div class="rounded-lg shadow bg-orange-500 text-white p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-bold">{{ $totalBerita }}</h1>
                <p class="text-lg mt-1">Berita & Kegiatan</p>
            </div>

            <svg class="w-20 h-20 opacity-30" fill="currentColor" viewBox="0 0 24 24">
                <path d="M10 17l5-5-5-5v10z"/>
            </svg>
        </div>

        <a href="{{ route('admin.beritadankegiatan.index') }}" class="block mt-4 text-sm bg-orange-600 hover:bg-orange-700 py-2 text-center rounded-md">
            More info →
        </a>
    </div>
</div>
<!-- Activity Log (TABLE) -->
<div class="mt-10">
    <h2 class="text-2xl font-bold text-green-700 mb-4">Aktivitas Terbaru</h2>

    <div class="overflow-x-auto bg-white shadow rounded-xl">
        <table class="min-w-full text-sm text-gray-700">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">Tanggal</th>
                    <th class="px-4 py-3 text-left font-semibold">Aktivitas</th>
                    <th class="px-4 py-3 text-left font-semibold">User</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($logs as $log)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3">
                        {{ $log->created_at->format('d M Y, H:i') }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $log->description }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $log->user->name ?? 'User tidak ditemukan' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-4 py-3 text-center text-gray-500">
                        Belum ada aktivitas.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        <a href="{{ route('admin.activitylog') }}"
           class="text-sm text-green-700 font-semibold hover:underline">
            Lihat semua aktivitas →
        </a>
    </div>
</div>


@endsection
