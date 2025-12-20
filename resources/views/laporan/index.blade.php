@extends('layouts.app')

@section('title', 'Laporan Keuangan - Masjid Baiturrahman')

@section('content')

<!-- HERO -->
<div class="bg-green-50 py-16">
  <div class="container mx-auto px-6">
    <h1 class="text-3xl font-bold text-gray-800">Laporan Keuangan</h1>
    <p class="text-gray-600 mt-2">
      Transparansi pemasukan & pengeluaran masjid
    </p>
  </div>
</div>

<!-- RINGKASAN -->
<section class="py-10 bg-white">
  <div class="container mx-auto px-6 grid md:grid-cols-3 gap-6">

    <div class="p-6 bg-green-50 rounded-2xl shadow">
      <h3 class="text-sm text-gray-600">Total Pemasukan</h3>
      <p class="text-2xl font-bold text-green-700">
        Rp {{ number_format($totalPemasukan,0,',','.') }}
      </p>
    </div>

    <div class="p-6 bg-red-50 rounded-2xl shadow">
      <h3 class="text-sm text-gray-600">Total Pengeluaran</h3>
      <p class="text-2xl font-bold text-red-600">
        Rp {{ number_format($totalPengeluaran,0,',','.') }}
      </p>
    </div>

    <div class="p-6 bg-blue-50 rounded-2xl shadow">
      <h3 class="text-sm text-gray-600">Saldo Akhir</h3>
      <p class="text-2xl font-bold text-blue-700">
        Rp {{ number_format($saldoAkhir,0,',','.') }}
      </p>
    </div>

  </div>
</section>

<!-- GRAFIK -->
<section class="py-16 bg-green-50">
  <div class="container mx-auto px-6">
    <div class="bg-white rounded-2xl shadow p-6">
      <h3 class="font-semibold text-lg mb-4">Grafik Kas Bulanan</h3>
      <canvas id="kasChart" height="120"></canvas>
    </div>
  </div>
</section>

<!-- TABEL -->
<section class="py-16 bg-white">
  <div class="container mx-auto px-6 grid md:grid-cols-2 gap-8">

    <!-- PEMASUKAN -->
    <div>
      <h3 class="font-semibold text-lg mb-4 text-green-700">Pemasukan</h3>
      <div class="space-y-3">
        @forelse($pemasukkans as $p)
          <div class="p-4 border rounded-xl flex justify-between">
            <div>
              <p class="font-medium">{{ $p->sumber_dana }}</p>
              <p class="text-sm text-gray-500">
                {{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('d F Y') }}
              </p>
            </div>
            <p class="font-semibold text-green-700">
              + Rp {{ number_format($p->jumlah_dana,0,',','.') }}
            </p>
          </div>
        @empty
          <p class="text-gray-500">Belum ada pemasukan.</p>
        @endforelse
      </div>
    </div>

    <!-- PENGELUARAN -->
    <div>
      <h3 class="font-semibold text-lg mb-4 text-red-600">Pengeluaran</h3>
      <div class="space-y-3">
        @forelse($pengeluarans as $k)
          <div class="p-4 border rounded-xl flex justify-between">
            <div>
              <p class="font-medium">{{ $k->kategori }}</p>
              <p class="text-sm text-gray-500">
                {{ \Carbon\Carbon::parse($k->tanggal)->translatedFormat('d F Y') }}
              </p>
            </div>
            <p class="font-semibold text-red-600">
              - Rp {{ number_format($k->jumlah_dana,0,',','.') }}
            </p>
          </div>
        @empty
          <p class="text-gray-500">Belum ada pengeluaran.</p>
        @endforelse
      </div>
    </div>

  </div>
</section>

<!-- CHART JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('kasChart');

new Chart(ctx, {
  type: 'line',
  data: {
    labels: @json($labels),
    datasets: [
      {
        label: 'Pemasukan',
        data: @json($dataMasuk),
        borderWidth: 2,
        tension: .4
      },
      {
        label: 'Pengeluaran',
        data: @json($dataKeluar),
        borderWidth: 2,
        tension: .4
      }
    ]
  }
});
</script>

@endsection
