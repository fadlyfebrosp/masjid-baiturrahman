@extends('admin.components.app')

@section('title', 'Donasi Offline')

@section('content')

<!-- ================= HEADER & BREADCRUMB ================= -->
<div class="mb-6 flex items-start justify-between">
    <h1 class="text-2xl font-bold text-green-700">
        Donasi Offline
    </h1>

    <nav class="text-sm text-gray-600">
        <a href="{{ route('admin.dashboard') }}" class="hover:underline">Dashboard</a>
            <span class="mx-1">›</span>
        <a href="{{ route('admin.contactdonasioffline.index') }}" class="font-semibold hover:underline">
            Donasi Offline
        </a>
    </nav>
</div>
<!-- ================= STAT CARDS ================= -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

    {{-- TOTAL DITERIMA --}}
    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-blue-600">
        <p class="text-sm text-gray-500">Total Diterima</p>
        <h2 class="text-2xl font-bold text-blue-700">
            Rp {{ number_format($totalDiterima, 0, ',', '.') }}
        </h2>
    </div>

    {{-- TOTAL PROSPEK --}}
    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-green-600">
        <p class="text-sm text-gray-500">Total Prospek</p>
        <h2 class="text-2xl font-bold text-green-700">
            Rp {{ number_format($totalProspek, 0, ',', '.') }}
        </h2>
    </div>

    {{-- TOTAL GAGAL --}}
    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-red-600">
        <p class="text-sm text-gray-500">Total Gagal</p>
        <h2 class="text-2xl font-bold text-red-700">
            Rp {{ number_format($totalGagal, 0, ',', '.') }}
        </h2>
    </div>

    {{-- TOTAL KONTAK --}}
    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-orange-600">
        <p class="text-sm text-gray-500">Total Contact</p>
        <h2 class="text-2xl font-bold text-orange-700">
            {{ $totalContact }}
        </h2>
    </div>

</div>
<!-- ================= ACTION BAR ================= -->
<div class="flex justify-between items-center mb-4">

    <!-- kiri -->
    <a href="{{ route('admin.donasioffline.tambah') }}"
       class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
        + Tambah
    </a>

    <!-- kanan -->
    <form method="GET" class="flex gap-2">
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               placeholder="Pencarian..."
               class="px-3 py-2 border rounded-lg text-sm focus:ring focus:ring-blue-200">

        <button class="px-4 py-2 bg-gray-100 border rounded-lg text-sm hover:bg-gray-200">
            Cari
        </button>
    </form>

</div>

<!-- ================= TABLE ================= -->
<div class="bg-white rounded-xl shadow overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-100 text-gray-600">
            <tr>
                <th class="px-4 py-3 text-left">Kode</th>
                <th class="px-4 py-3 text-left">Nama</th>
                <th class="px-4 py-3 text-left">Program</th>
                <th class="px-4 py-3 text-right">Nominal</th>
                <th class="px-4 py-3 text-center">Status</th>
            </tr>
        </thead>

        <tbody class="divide-y">
            @forelse ($donasi as $item)
                <tr>
                    <td class="px-4 py-3">
                        {{ $item->kode_transaksi }}
                    </td>

                    <td class="px-4 py-3">
                        <div class="font-medium">
                            {{ data_get($item, 'contact.name', '-') }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $item->contact->email ?? '-' }}
                        </div>
                    </td>

                    <td class="px-4 py-3">
                        @if($item->program)
                            <div class="font-medium">{{ $item->program->judul }}</div>
                            <div class="text-xs text-gray-500">
                                {{ strtoupper($item->program->kategori) }}
                            </div>
                        @else
                            -
                        @endif
                    </td>

                    <td class="px-4 py-3 text-right">
                        Rp {{ number_format($item->nominal, 0, ',', '.') }}
                    </td>

                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-1 rounded-full text-xs
                            {{ $item->status === 'sukses'
                                ? 'bg-green-100 text-green-700'
                                : 'bg-yellow-100 text-yellow-700' }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-8 text-gray-500">
                        Belum ada data
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- ================= PAGINATION ================= -->
<div class="mt-4">
    {{ $donasi->links() }}
</div>

@endsection
