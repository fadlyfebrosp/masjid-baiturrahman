<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan Masjid</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        h1, h2 {
            text-align: center;
            margin: 4px 0;
        }

        .info {
            text-align: center;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        th, td {
            border: 1px solid #000;
            padding: 5px;
        }

        th {
            background: #0f9d58;
            color: #fff;
        }

        .right {
            text-align: right;
        }

        .section-title {
            font-weight: bold;
            margin: 10px 0 5px;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
        }
    </style>
</head>
<body>

<h1>LAPORAN KEUANGAN MASJID</h1>

<div class="info">
    Periode: {{ $periode }} <br>
    Dicetak: {{ $tanggalCetak }}
</div>

{{-- PEMASUKKAN --}}
<div class="section-title">(A) PEMASUKKAN</div>
<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Sumber Dana</th>
            <th>Jumlah</th>
        </tr>
    </thead>
    <tbody>
        @foreach($dataPemasukkan as $item)
        <tr>
            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
            <td>{{ $item->sumber_dana }}</td>
            <td class="right">Rp {{ number_format($item->jumlah_dana,0,',','.') }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2" class="right">JUMLAH PEMASUKKAN</td>
            <td class="right">Rp {{ number_format($totalPemasukkan,0,',','.') }}</td>
        </tr>
    </tfoot>
</table>

{{-- PENGELUARAN --}}
<div class="section-title">(B) PENGELUARAN</div>
<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Kategori</th>
            <th>Jumlah</th>
        </tr>
    </thead>
    <tbody>
        @foreach($dataPengeluaran as $item)
        <tr>
            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
            <td>{{ $item->kategori }}</td>
            <td class="right">Rp {{ number_format($item->jumlah_dana,0,',','.') }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2" class="right">JUMLAH PENGELUARAN</td>
            <td class="right">Rp {{ number_format($totalPengeluaran,0,',','.') }}</td>
        </tr>
    </tfoot>
</table>

{{-- SALDO --}}
<table>
    <tr>
        <th>SALDO AKHIR (A − B)</th>
        <th class="right">Rp {{ number_format($saldoAkhir,0,',','.') }}</th>
    </tr>
</table>

<div class="footer">
    Bogor, {{ $tanggalCetak }} <br><br>
    Bendahara ____________________
</div>

</body>
</html>
