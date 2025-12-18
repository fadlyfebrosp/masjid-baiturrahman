<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Pengeluaran</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        h1 {
            text-align: center;
            margin-bottom: 6px;
            font-size: 16px;
        }

        .info {
            text-align: center;
            font-size: 11px;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background: #c62828;
            color: #fff;
            text-align: center;
        }

        .right {
            text-align: right;
        }

        tfoot td {
            font-weight: bold;
        }
    </style>
</head>
<body>

<h1>LAPORAN PENGELUARAN</h1>

<div class="info">
    Periode: {{ $periode }} <br>
    Tanggal Cetak: {{ $tanggalCetak }}
</div>

<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Kategori</th>
            <th>Jumlah</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $item)
        <tr>
            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
            <td>{{ $item->kategori }}</td>
            <td class="right">
                Rp {{ number_format($item->jumlah_dana,0,',','.') }}
            </td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2" class="right">TOTAL</td>
            <td class="right">
                Rp {{ number_format($total,0,',','.') }}
            </td>
        </tr>
    </tfoot>
</table>

</body>
</html>
