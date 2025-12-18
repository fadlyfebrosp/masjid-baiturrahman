@extends('finance.components.app')

@section('title', 'Transaction')

@section('content')

@php
    // Normalisasi kategori dari route
    $kategoriAktif = request()->route('kategori');
    $kategoriLabel = $kategoriAktif
        ? ucfirst($kategoriAktif)
        : 'Semua';
@endphp

<!-- ================= JUDUL HALAMAN ================= -->
<div class="mb-6">
  <div class="flex justify-between items-center mb-6">
    <div>
      <h1 class="text-3xl font-bold text-green-700">
        Transaction {{ $kategoriAktif ? ' - '.$kategoriLabel : '' }}
      </h1>
      <p class="text-sm text-gray-500 mt-1">
        Data transaksi donasi {{ strtolower($kategoriLabel) }}
      </p>
    </div>

    <!-- BREADCRUMB -->
    <div class="text-sm text-gray-600">
        <a href="{{ route('finance.dashboard') }}" class="hover:underline">Home</a>
        <span class="mx-1">></span>
        <a href="{{ route('finance.transaction') }}" class="hover:underline">Transaction</a>

        @if ($kategoriAktif)
            <span class="mx-1">></span>
            <span class="font-semibold text-gray-800 capitalize">
                {{ $kategoriLabel }}
            </span>
        @endif
    </div>
  </div>
</div>

<!-- ================= INFO KATEGORI ================= -->
@if ($kategoriAktif)
<div class="mb-4">
    <span class="inline-flex items-center px-4 py-1.5 rounded-full
        bg-green-100 text-green-700 text-sm font-semibold">
        Kategori: {{ $kategoriLabel }}
    </span>
</div>
@endif

<!-- ================= TABLE TRANSACTION ================= -->
<div class="bg-white rounded-xl shadow overflow-hidden">

  <div class="p-6 border-b flex justify-between items-center">
    <h2 class="text-xl font-bold text-green-700">
        Data Transaksi Donasi
    </h2>

    <span class="text-sm text-gray-500">
        Total: {{ $transactions->total() }} transaksi
    </span>
  </div>

  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-green-700 text-white">
        <tr>
          <th class="px-4 py-3 text-left">No</th>
          <th class="px-4 py-3 text-left">Donatur</th>
          <th class="px-4 py-3 text-left">Program</th>
          <th class="px-4 py-3 text-left">Kategori</th>
          <th class="px-4 py-3 text-left">Metode</th>
          <th class="px-4 py-3 text-right">Nominal</th>
          <th class="px-4 py-3 text-center">Status</th>
          <th class="px-4 py-3 text-center">Tanggal</th>
        </tr>
      </thead>

      <tbody class="divide-y">
        @forelse ($transactions as $item)
          @php
            $program   = $item->donasi->program ?? null;
            $kategori  = $program->kategori ?? '-';
            $status    = $item->status ?? 'pending';
          @endphp

          <tr class="hover:bg-gray-50">
            <td class="px-4 py-3">
                {{ $transactions->firstItem() + $loop->index }}
            </td>

            <td class="px-4 py-3">
              {{ $item->anonim
                    ? 'Anonim'
                    : ($item->user->name ?? $item->nama_donatur ?? '-') }}
            </td>

            <td class="px-4 py-3">
              {{ $program->judul ?? '-' }}
            </td>

            <td class="px-4 py-3">
              <span class="px-2 py-0.5 rounded-md text-xs font-semibold bg-gray-100 text-gray-700">
                {{ $kategori }}
              </span>
            </td>

            <td class="px-4 py-3">
              {{ $item->payment_method ?? '-' }}
            </td>

            <td class="px-4 py-3 text-right font-semibold">
              Rp {{ number_format($item->amount, 0, ',', '.') }}
            </td>

            <td class="px-4 py-3 text-center">
              <span class="px-3 py-1 rounded-full text-xs font-semibold
                {{ $status === 'paid'
                    ? 'bg-green-100 text-green-700'
                    : ($status === 'pending'
                        ? 'bg-yellow-100 text-yellow-700'
                        : 'bg-red-100 text-red-700') }}">
                {{ ucfirst($status) }}
              </span>
            </td>

            <td class="px-4 py-3 text-center text-gray-600">
              {{ $item->paid_at
                  ? \Carbon\Carbon::parse($item->paid_at)->format('d M Y')
                  : '-' }}
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="text-center py-6 text-gray-500">
              Tidak ada transaksi untuk kategori ini
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <!-- PAGINATION -->
  <div class="p-4 border-t">
    {{ $transactions->withQueryString()->links() }}
  </div>

</div>
@endsection
