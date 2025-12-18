<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pemasukkan Masjid</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .header h1 {
            font-size: 16px;
            margin: 0;
            text-transform: uppercase;
        }

        .header p {
            margin: 2px 0;
            font-size: 11px;
        }

        .info {
            margin-bottom: 10px;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background-color: #0f9d58;
            color: #fff;
            text-align: center;
        }

        td {
            vertical-align: top;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        tfoot td {
            font-weight: bold;
            background-color: #f2f2f2;
        }

        .footer {
            margin-top: 35px;
            width: 100%;
        }

        .ttd {
            float: right;
            text-align: center;
            font-size: 11px;
        }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <div class="header">
        <h1>Laporan Pemasukkan Masjid</h1>
        <p>Periode: {{ $periode }}</p>
        <p>Tanggal Cetak: {{ $tanggalCetak }}</p>
    </div>

    {{-- TABEL PEMASUKKAN --}}
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Sumber Dana</th>
                <th>Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dataPemasukkan as $item)
            <tr>
                <td class="center">
                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}
                </td>
                <td>{{ $item->sumber_dana }}</td>
                <td class="right">
                    {{ number_format($item->jumlah_dana, 0, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="right">TOTAL PEMASUKKAN</td>
                <td class="right">
                    {{ number_format($totalPemasukkan, 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
    </table>

    {{-- FOOTER --}}
    <div class="footer">
        <div class="ttd">
            Bogor, {{ $tanggalCetak }}<br><br><br>
            <strong>Bendahara</strong><br>
            ( ____________________ )
        </div>
    </div>

</body>
</html>
