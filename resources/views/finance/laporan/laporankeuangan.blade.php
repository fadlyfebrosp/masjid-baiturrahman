@extends('finance.components.app')

@section('title', 'Laporan Keuangan')

@section('content')

<div class="mb-6">
    <div class="flex justify-between items-center">

        <!-- JUDUL -->
        <h1 class="text-3xl font-bold text-green-700">
            Laporan Keuangan
        </h1>

        <!-- BREADCRUMB -->
        <nav class="text-sm text-gray-600">
            <a href="{{ route('finance.dashboard') }}"
               class="hover:text-green-700 hover:underline">
                Home
            </a>

            <span class="mx-1">›</span>

            <span class="font-semibold text-gray-800">
                Laporan Keuangan
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

    <!-- EXPORT -->
    <button onclick="exportPdf()"
            class="bg-green-600 text-white px-4 py-2 rounded">
        Export PDF
    </button>

</div>

{{-- PEMASUKKAN --}}
<div class="bg-white rounded shadow mb-6">
    <h2 class="font-bold text-green-700 p-3">(A) Pemasukkan</h2>
    <table class="w-full text-sm">
        <thead class="bg-green-700 text-white">
            <tr>
                <th class="p-2">Tanggal</th>
                <th class="p-2">Sumber Dana</th>
                <th class="p-2 text-right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dataPemasukkan as $item)
            <tr class="border-b">
                <td class="p-2">{{ $item->tanggal }}</td>
                <td class="p-2">{{ $item->sumber_dana }}</td>
                <td class="p-2 text-right">
                    Rp {{ number_format($item->jumlah_dana,0,',','.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot class="bg-gray-100 font-bold">
            <tr>
                <td colspan="2" class="p-2 text-right">TOTAL PEMASUKKAN</td>
                <td class="p-2 text-right">
                    Rp {{ number_format($totalPemasukkan,0,',','.') }}
                </td>
            </tr>
        </tfoot>
    </table>
</div>

{{-- PENGELUARAN --}}
<div class="bg-white rounded shadow mb-6">
    <h2 class="font-bold text-green-700 p-3">(B) Pengeluaran</h2>
    <table class="w-full text-sm">
        <thead class="bg-green-700 text-white">
            <tr>
                <th class="p-2">Tanggal</th>
                <th class="p-2">Kategori</th>
                <th class="p-2 text-right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dataPengeluaran as $item)
            <tr class="border-b">
                <td class="p-2">{{ $item->tanggal }}</td>
                <td class="p-2">{{ $item->kategori }}</td>
                <td class="p-2 text-right">
                    Rp {{ number_format($item->jumlah_dana,0,',','.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot class="bg-gray-100 font-bold">
            <tr>
                <td colspan="2" class="p-2 text-right">TOTAL PENGELUARAN</td>
                <td class="p-2 text-right">
                    Rp {{ number_format($totalPengeluaran,0,',','.') }}
                </td>
            </tr>
        </tfoot>
    </table>
</div>

{{-- SALDO --}}
<div class="bg-white rounded shadow p-4 font-bold text-lg">
    Saldo Akhir :
    <span class="float-right">
        Rp {{ number_format($saldoAkhir,0,',','.') }}
    </span>
</div>

<script>
function exportPdf() {
    const start = document.getElementById('start_date').value;
    const end   = document.getElementById('end_date').value;

    let url = "{{ route('finance.laporan.laporankeuangan.pdf') }}";

    if (start && end) {
        if (!confirm(`Export laporan periode ${start} s/d ${end}?`)) return;
        url += `?start_date=${start}&end_date=${end}`;
    }

    window.location.href = url;
}
</script>

@endsection
