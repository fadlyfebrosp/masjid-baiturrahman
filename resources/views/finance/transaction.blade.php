@extends('finance.components.app')

@section('title', 'Transaction')

@section('content')
<!-- Judul Halaman -->
<div class="mb-6">
  <div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-green-700">Transaction</h1>

    <div class="text-sm text-gray-600">
        <a href="{{ route('finance.dashboard') }}" class="hover:underline">Home</a>
        <span class="mx-1">></span>
        <span class="font-semibold text-gray-800">Transaction</span>
    </div>
  </div>
</div>

<!-- TABLE TRANSACTION -->
<div class="bg-white rounded-xl shadow overflow-hidden">
  <div class="p-6 border-b">
    <h2 class="text-xl font-bold text-green-700">Data Transaksi Donasi</h2>
  </div>

  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-green-700 text-white">
        <tr>
          <th class="px-4 py-3 text-left">No</th>
          <th class="px-4 py-3 text-left">Donatur</th>
          <th class="px-4 py-3 text-left">Program</th>
          <th class="px-4 py-3 text-left">Metode</th>
          <th class="px-4 py-3 text-right">Nominal</th>
          <th class="px-4 py-3 text-center">Status</th>
          <th class="px-4 py-3 text-center">Tanggal</th>
        </tr>
      </thead>

      <tbody class="divide-y">
        @forelse ($transactions as $item)
          <tr class="hover:bg-gray-50">
            <td class="px-4 py-3">{{ $loop->iteration }}</td>

            <td class="px-4 py-3">
              {{ $item->anonim ? 'Anonim' : ($item->user->name ?? $item->nama_donatur) }}
            </td>

            <td class="px-4 py-3">
              {{ $item->program->judul ?? '-' }}
            </td>

            <td class="px-4 py-3">
              {{ $item->transaction->payment_method ?? '-' }}
            </td>

            <td class="px-4 py-3 text-right font-semibold">
              Rp {{ number_format($item->nominal, 0, ',', '.') }}
            </td>

            <td class="px-4 py-3 text-center">
              @php
                $status = $item->transaction->status ?? 'pending';
              @endphp

              <span class="px-3 py-1 rounded-full text-xs font-semibold
                {{ $status === 'paid'
                    ? 'bg-green-100 text-green-700'
                    : 'bg-yellow-100 text-yellow-700' }}">
                {{ ucfirst($status) }}
              </span>
            </td>

            <td class="px-4 py-3 text-center text-gray-600">
              {{ optional($item->transaction)->paid_at
                  ? \Carbon\Carbon::parse($item->transaction->paid_at)->format('d M Y')
                  : '-' }}
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center py-6 text-gray-500">
              Belum ada data transaksi
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
