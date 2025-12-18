@extends('finance.components.app')

@section('title', 'Laporan Pemasukkan')

@section('content')

<h1 class="text-2xl font-bold mb-4 text-green-700">
  Laporan Pemasukkan
</h1>

<form method="GET" class="flex gap-4 mb-4">
  <input type="date" name="start_date" value="{{ request('start_date') }}" class="border p-2">
  <input type="date" name="end_date" value="{{ request('end_date') }}" class="border p-2">
  <button class="bg-green-700 text-white px-4 py-2 rounded">Filter</button>
</form>

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
      @foreach($data as $item)
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
          Rp {{ number_format($total,0,',','.') }}
        </td>
      </tr>
    </tfoot>
  </table>
</div>

@endsection
