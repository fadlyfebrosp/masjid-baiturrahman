{{-- program/donasi/index.blade.php --}}
@extends('layouts.app')

@section('title', $kategori . ' - Masjid Baiturrahman')

@section('content')

{{-- ================= HEADER ================= --}}
<div class="bg-green-50 py-10">
    <div class="container mx-auto px-6">
        <h1 class="text-2xl font-bold text-gray-800"><i>Program</i> / {{ $kategori }}</h1>
    </div>
</div>

{{-- ================= BAGIAN KHUSUS ZAKAT ================= --}}
@if ($kategori === 'Zakat')
<div class="px-4 py-5 bg-white">
    <h2 class="text-lg font-semibold text-gray-800 mb-1">Siap bayar zakat?</h2>
    <p class="text-sm text-gray-600 mb-4">Hitung dan salurkan ke lembaga amil terpercaya</p>

    <div class="bg-white border rounded-xl p-4 shadow-sm flex items-center gap-4">
        <div class="text-4xl">🧮</div>

        <div class="flex-1">
            <p class="text-base font-semibold text-gray-800">Kalkulator Zakat</p>
            <p class="text-xs text-gray-500">Hitung kewajiban zakat profesi, fitrah dan maal kamu</p>
        </div>

        <a href="#" class="px-4 py-2 bg-green-600 text-white rounded-full text-sm font-medium">Hitung</a>
    </div>
</div>
@endif

{{-- ================= SECTION LIST PROGRAM ================= --}}
<section class="bg-white py-6">
    <div class="px-4 mb-4">
        <h2 class="text-base font-semibold text-gray-800">{{ $kategori }} ke program spesifik</h2>
        <p class="text-xs text-gray-600">Salurkan {{ strtolower($kategori) }} ke program terverifikasi</p>
    </div>

    <div class="px-4">
        <p class="text-sm font-semibold text-orange-600 mb-3">REKOMENDASI</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

            @foreach ($data as $item)

            @php
                // Hitung progress
                $terkumpul = $item->terkumpul ?? 0;
                $target = $item->target_dana ?? 1;
                $persen = min(100, ($terkumpul / $target) * 100);

                // Total jumlah donasi
                $jumlahDonasi = $item->jumlah_donasi ?? 0;

                // Hitung sisa hari
                if ($item->open_goals) {
                    $sisaHari = "Tanpa Batas Waktu";
                } else {
                    if ($item->target_waktu) {
                        $sisa = now()->startOfDay()->diffInDays(
                            \Carbon\Carbon::parse($item->target_waktu)->startOfDay(),
                            false
                        );
                        $sisaHari = $sisa > 0 ? ceil($sisa) . " Hari" : "Berakhir";
                    } else {
                        $sisaHari = "Belum diatur";
                    }
                }
            @endphp

            <div class="program-card w-full max-w-[300px] bg-white border border-green-400 rounded-2xl shadow hover:shadow-md transition overflow-hidden">

                {{-- FOTO --}}
                <div class="relative group">
                    <img
                        src="{{ $item->foto ? asset('storage/' . $item->foto) : asset('assets/img/program/default.jpg') }}"
                        alt="{{ $item->judul }}"
                        class="
                            w-full
                            h-48 md:h-56 lg:h-60
                            object-cover object-center
                            transition-transform duration-500
                            group-hover:scale-105
                        ">

                    <span class="absolute top-2 left-2 bg-green-600 text-white text-[10px] px-2 py-0.5 rounded-md uppercase tracking-wide">
                        {{ $item->kategori }}
                    </span>
                </div>

                {{-- KONTEN PROGRAM --}}
                <div class="p-4 space-y-3 text-left">

                    {{-- JUDUL --}}
                    <h1 class="text-base font-semibold text-gray-900 leading-snug">
                        {{ $item->judul }}
                    </h1>

                    {{-- TOTAL & TARGET --}}
                    <div class="text-gray-700 text-sm leading-tight">
                        <span class="font-semibold text-black">
                            Rp {{ number_format($terkumpul, 0, ',', '.') }}
                        </span>
                        <span> terkumpul dari </span>
                        <span class="font-bold">
                            Rp {{ number_format($target, 0, ',', '.') }}
                        </span>
                    </div>

                    {{-- PROGRESS BAR --}}
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full"
                            style="width: {{ $persen }}%">
                        </div>
                    </div>

                    {{-- JUMLAH DONASI + SISA WAKTU --}}
                    <div class="flex justify-between text-gray-600 text-xs w-full">

                        <span>{{ $jumlahDonasi }} Donasi</span>

                        <div class="flex flex-col items-end leading-tight">
                            <span class="font-medium text-gray-700">Sisa Waktu:</span>
                            <span class="text-gray-500">{{ $sisaHari }}</span>
                        </div>
                    </div>

                    {{-- BUTTON DONASI --}}
                    <a href="{{ route('program.detail', [
                            'kategori' => strtolower($item->kategori),
                            'slug'     => $item->slug
                        ]) }}"
                        class="block w-full bg-green-600 text-white text-center py-2.5 rounded-lg text-sm font-semibold hover:bg-green-700">
                        Infaq Sekarang!
                    </a>

                </div>
            </div>
            @endforeach

        </div>

        <div class="mt-5">
            {{ $data->links() }}
        </div>
    </div>
</section>

@endsection
