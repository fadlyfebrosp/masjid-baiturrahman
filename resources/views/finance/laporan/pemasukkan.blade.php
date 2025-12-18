@extends('finance.components.app')

@section('title', 'Laporan Pemasukkan')

@section('content')

<div class="mb-6">
    <div class="flex justify-between items-center">

        <!-- JUDUL -->
        <h1 class="text-3xl font-bold text-green-700">
            Laporan Pemasukkan
        </h1>

        <!-- BREADCRUMB -->
        <nav class="text-sm text-gray-600">
            <a href="{{ route('finance.dashboard') }}"
               class="hover:text-green-700 hover:underline">
                Home
            </a>

            <span class="mx-1">›</span>

            <a href="{{ route('finance.laporan.laporankeuangan') }}"
               class="hover:text-green-700 hover:underline">
                Laporan Keuangan
            </a>

            <span class="mx-1">›</span>

            <span class="font-semibold text-gray-800">
                Pemasukkan
            </span>
        </nav>

    </div>
</div>

<div class="flex justify-between items-end mb-4">

    <!-- FILTER -->
    <form method="GET" id="filterForm" class="flex gap-4">
        <input type="date"
               name="start_date"
               id="start_date"
               value="{{ request('start_date') }}"
               class="border p-2 rounded">

        <input type="date"
               name="end_date"
               id="end_date"
               value="{{ request('end_date') }}"
               class="border p-2 rounded">

        <button class="bg-green-700 text-white px-4 py-2 rounded">
            Filter
        </button>
    </form>

    <!-- EXPORT PDF -->
    <button onclick="exportPdf()"
            class="bg-green-600 text-white px-4 py-2 rounded">
        Export PDF
    </button>

</div>



<div class="bg-white rounded shadow overflow-hidden">
  <table class="w-full text-sm">
    <thead class="bg-green-700 text-white">
      <tr>
        <th class="p-3">Tanggal</th>
        <th class="p-3">Sumber Dana</th>
        <th class="p-3 text-right">Jumlah</th>
      </tr>
    </thead>
    <tbody>
      @foreach($dataPemasukkan as $item)
      <tr class="border-b">
        <td class="p-3">{{ $item->tanggal }}</td>
        <td class="p-3">{{ $item->sumber_dana }}</td>
        <td class="p-3 text-right">
          Rp {{ number_format($item->jumlah_dana,0,',','.') }}
        </td>
      </tr>
      @endforeach
    </tbody>
    <tfoot class="bg-gray-100 font-bold">
      <tr>
        <td colspan="2" class="p-3 text-right">TOTAL</td>
        <td class="p-3 text-right">
          Rp {{ number_format($totalPemasukkan,0,',','.') }}
        </td>
      </tr>
    </tfoot>
  </table>
</div>
<script>
function exportPdf() {
    const start = document.getElementById('start_date').value;
    const end   = document.getElementById('end_date').value;

    let url = "{{ route('finance.laporan.pemasukkan.pdf') }}";


    if (start && end) {
        const confirmRange = confirm(
            "Anda akan mengekspor laporan dengan RENTANG TANGGAL.\n\n" +
            "Periode: " + start + " s/d " + end + "\n\nLanjutkan?"
        );

        if (!confirmRange) return;

        url += `?start_date=${start}&end_date=${end}`;
    }

    window.location.href = url;
}
</script>


@endsection
