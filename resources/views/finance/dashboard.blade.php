@extends('finance.components.app')

@section('title', 'Dashboard Finance')

@section('content')

<!-- ================= HEADER ================= -->
<div class="mb-6">
  <h1 class="text-3xl font-bold text-green-700">Dashboard Finance</h1>
</div>

<!-- ================= CARD ================= -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6">

  <!-- TRANSACTION -->
  <div class="bg-white p-6 rounded-xl shadow">
    <h2 class="text-sm text-gray-500">Total Transaksi</h2>
    <p class="text-2xl font-bold text-blue-600">
      {{ $totalTransaksi }}
    </p>
  </div>

  <!-- PEMASUKKAN -->
  <div class="bg-white p-6 rounded-xl shadow">
    <h2 class="text-sm text-gray-500">Total Pemasukkan</h2>
    <p class="text-2xl font-bold text-green-700">
      Rp {{ number_format($totalPemasukkan,0,',','.') }}
    </p>
  </div>

  <!-- PENGELUARAN -->
  <div class="bg-white p-6 rounded-xl shadow">
    <h2 class="text-sm text-gray-500">Total Pengeluaran</h2>
    <p class="text-2xl font-bold text-red-600">
      Rp {{ number_format($totalPengeluaran,0,',','.') }}
    </p>
  </div>

  <!-- SALDO -->
  <div class="bg-white p-6 rounded-xl shadow">
    <h2 class="text-sm text-gray-500">Saldo Kas</h2>
    <p class="text-2xl font-bold {{ $saldoKas < 0 ? 'text-red-600' : 'text-green-700' }}">
      Rp {{ number_format($saldoKas,0,',','.') }}
    </p>
  </div>

</div>

<!-- ================= GRAFIK ================= -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-10">

  <!-- GRAFIK KAS -->
  <div class="bg-white p-6 rounded-xl shadow">
    <h3 class="font-semibold mb-4 text-green-700">
      Pemasukkan & Pengeluaran (12 Bulan)
    </h3>
    <canvas id="kasChart"></canvas>
  </div>

  <!-- GRAFIK TRANSAKSI -->
  <div class="bg-white p-6 rounded-xl shadow">
    <h3 class="font-semibold mb-4 text-blue-600">
      Transaksi Donasi (12 Bulan)
    </h3>
    <canvas id="transaksiChart"></canvas>
  </div>

</div>

<!-- ================= SCRIPT ================= -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

  // ===== GRAFIK KAS =====
  new Chart(document.getElementById('kasChart'), {
    type: 'line',
    data: {
      labels: @json($labels),
      datasets: [
        {
          label: 'Pemasukkan',
          data: @json($dataPemasukkan),
          borderWidth: 2,
          tension: 0.4
        },
        {
          label: 'Pengeluaran',
          data: @json($dataPengeluaran),
          borderWidth: 2,
          tension: 0.4
        }
      ]
    }
  });

  // ===== GRAFIK TRANSAKSI =====
  new Chart(document.getElementById('transaksiChart'), {
    type: 'bar',
    data: {
      labels: @json($labels),
      datasets: [{
        label: 'Jumlah Transaksi',
        data: @json($dataTransaksi),
        borderWidth: 1,
        borderRadius: 6
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true,
          ticks: { precision: 0 }
        }
      }
    }
  });

});
</script>

@endsection
