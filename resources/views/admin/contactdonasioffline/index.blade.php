@extends('admin.components.app')

@section('title', 'Kontak Donasi Offline')

@section('content')

<!-- ================= HEADER & BREADCRUMB ================= -->
<div class="mb-6 flex flex-col gap-2 md:flex-row md:items-start md:justify-between">
    <h1 class="text-2xl font-bold text-green-700">
        Kontak Donasi Offline
    </h1>

    <nav class="text-sm text-gray-600">
        <a href="{{ route('admin.dashboard') }}" class="hover:underline">Dashboard</a>
        <span class="mx-1">›</span>
        <a href="{{ route('admin.contactdonasioffline.index') }}" class="font-semibold hover:underline">
            Kontak Donasi Offline
        </a>
    </nav>
</div>

<!-- ================= STAT CARDS ================= -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-blue-600">
        <p class="text-sm text-gray-500">Kelolaan</p>
        <h2 class="text-2xl font-bold">
            {{ $totalAkunDanKelolaan }}
        </h2>
    </div>

    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-blue-600">
        <p class="text-sm text-gray-500">Total Kontak</p>
        <h2 class="text-2xl font-bold">
            {{ $totalContacts }}
        </h2>
    </div>

    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-green-600">
        <p class="text-sm text-gray-500">Order Rate</p>
        <h2 class="text-2xl font-bold text-green-700">
            {{ number_format($orderRate * 100, 1) }}%
        </h2>
        <p class="text-xs text-gray-400 mt-1">
            Rasio donasi berhasil per kontak
        </p>
    </div>

    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-pink-600">
        <p class="text-sm text-gray-500">Average Revenue</p>
        <h2 class="text-2xl font-bold text-pink-700">
            Rp {{ number_format($averageRevenue, 0, ',', '.') }}
        </h2>
    </div>

</div>

<!-- ================= ACTION BAR ================= -->
<div class="flex justify-end mb-4">
    <a href="{{ route('admin.contactdonasioffline.tambah') }}"
       class="w-full md:w-auto text-center px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
        + Tambah Data
    </a>
</div>

<!-- ================= DESKTOP TABLE ================= -->
<div class="hidden md:block bg-white rounded-xl shadow overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-100 text-gray-600">
            <tr>
                <th class="px-4 py-3 text-left">Nama</th>
                <th class="px-4 py-3 text-left">Email</th>
                <th class="px-4 py-3 text-left">Telepon</th>
                <th class="px-4 py-3 text-left">Gender</th>
                <th class="px-4 py-3 text-left">Kota</th>
                <th class="px-4 py-3 text-center">Aksi</th>
            </tr>
        </thead>

        <tbody>
        @forelse ($contacts as $item)
            <tr class="border-t hover:bg-gray-50">
                <td class="px-4 py-3 font-medium">
                    <a href="{{ route('admin.contactdonasioffline.show', $item->id) }}"
                       class="text-green-700 hover:underline">
                        {{ $item->name }}
                    </a>
                </td>
                <td class="px-4 py-3">{{ $item->email ?? '-' }}</td>
                <td class="px-4 py-3">{{ $item->phone ?? '-' }}</td>
                <td class="px-4 py-3 capitalize">{{ $item->gender ?? '-' }}</td>
                <td class="px-4 py-3">{{ $item->city ?? '-' }}</td>
                <td class="px-4 py-3 text-center">
                    <div class="flex justify-center gap-2">
                        <a href="{{ route('admin.contactdonasioffline.edit', $item->id) }}"
                           class="px-3 py-1 text-xs rounded bg-yellow-100 text-yellow-700 hover:bg-yellow-200">
                            Edit
                        </a>

                        <form action="{{ route('admin.contactdonasioffline.destroy', $item->id) }}"
                              method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus kontak ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="px-3 py-1 text-xs rounded bg-red-100 text-red-700 hover:bg-red-200">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center py-6 text-gray-500">
                    Belum ada data kontak
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<!-- ================= MOBILE CARD VIEW ================= -->
<div class="md:hidden space-y-3">
@forelse ($contacts as $item)
    <div class="bg-white rounded-xl shadow p-4">
        <h3 class="font-semibold text-green-700">
            {{ $item->name }}
        </h3>

        <div class="text-sm text-gray-600 mt-2 space-y-1">
            <p><span class="font-medium">Email:</span> {{ $item->email ?? '-' }}</p>
            <p><span class="font-medium">Telepon:</span> {{ $item->phone ?? '-' }}</p>
            <p><span class="font-medium">Gender:</span> {{ ucfirst($item->gender ?? '-') }}</p>
            <p><span class="font-medium">Kota:</span> {{ $item->city ?? '-' }}</p>
        </div>

        <div class="flex gap-2 mt-4">
            <a href="{{ route('admin.contactdonasioffline.edit', $item->id) }}"
               class="flex-1 text-center px-3 py-2 text-xs rounded bg-yellow-100 text-yellow-700 hover:bg-yellow-200">
                Edit
            </a>

            <form action="{{ route('admin.contactdonasioffline.destroy', $item->id) }}"
                  method="POST"
                  onsubmit="return confirm('Yakin ingin menghapus kontak ini?')"
                  class="flex-1">
                @csrf
                @method('DELETE')
                <button class="w-full px-3 py-2 text-xs rounded bg-red-100 text-red-700 hover:bg-red-200">
                    Hapus
                </button>
            </form>
        </div>
    </div>
@empty
    <div class="text-center text-gray-500 py-6">
        Belum ada data kontak
    </div>
@endforelse
</div>

<!-- ================= PAGINATION ================= -->
<div class="mt-4">
    {{ $contacts->links() }}
</div>

@endsection
