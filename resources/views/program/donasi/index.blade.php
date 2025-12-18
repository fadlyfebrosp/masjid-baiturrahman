{{-- program/donasi/index.blade.php --}}
@extends('layouts.app')

@section('title', $kategori . ' - Masjid Baiturrahman')

@section('content')

{{-- ================= HEADER ================= --}}
<div class="bg-green-50 py-10">
    <div class="container mx-auto px-6">
        <h1 class="text-2xl font-bold text-gray-800">
            <i>Program</i> / {{ $kategori }}
        </h1>
    </div>
</div>

{{-- ================= BAGIAN KHUSUS ZAKAT ================= --}}
@if ($kategori === 'Zakat')
<div class="px-4 py-5 bg-white">
    <h2 class="text-lg font-semibold text-gray-800 mb-1">Siap bayar zakat?</h2>
    <p class="text-sm text-gray-600 mb-4">
        Hitung dan salurkan ke lembaga amil terpercaya
    </p>

    <div class="bg-white border rounded-xl p-4 shadow-sm flex items-center gap-4">
        <div class="text-4xl">🧮</div>

        <div class="flex-1">
            <p class="text-base font-semibold text-gray-800">Kalkulator Zakat</p>
            <p class="text-xs text-gray-500">
                Hitung kewajiban zakat profesi, fitrah dan maal kamu
            </p>
        </div>

        <a href="#"
           class="px-4 py-2 bg-green-600 text-white rounded-full text-sm font-medium">
            Hitung
        </a>
    </div>
</div>
@endif

{{-- ================= SECTION LIST PROGRAM ================= --}}
<section class="bg-white py-6">
    <div class="px-4 mb-4">
        <h2 class="text-base font-semibold text-gray-800">
            {{ $kategori }} ke program spesifik
        </h2>
        <p class="text-xs text-gray-600">
            Salurkan {{ strtolower($kategori) }} ke program terverifikasi
        </p>
    </div>

    <div class="px-4">
        <p class="text-sm font-semibold text-orange-600 mb-3">REKOMENDASI</p>

        {{-- GRID PROGRAM --}}
        <div id="programGrid"
             class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">

            @foreach ($data as $item)

            @php
                $terkumpul = $item->terkumpul ?? 0;
                $target = $item->target_dana ?? 1;
                $persen = min(100, ($terkumpul / $target) * 100);
                $jumlahDonasi = $item->jumlah_donasi ?? 0;

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

            {{-- CARD --}}
            <div class="program-card hidden w-full bg-white border border-green-400 rounded-xl shadow hover:shadow-md transition overflow-hidden">

                {{-- FOTO --}}
                <div class="relative group">
                    <img
                        src="{{ $item->foto ? asset('storage/' . $item->foto) : asset('assets/img/program/default.jpg') }}"
                        alt="{{ $item->judul }}"
                        class="w-full h-36 md:h-40 lg:h-44 object-cover transition-transform duration-500 group-hover:scale-105">

                    <span class="absolute top-2 left-2 bg-green-600 text-white text-[10px] px-2 py-0.5 rounded-md uppercase">
                        {{ $item->kategori }}
                    </span>
                </div>

                {{-- KONTEN --}}
                <div class="p-3 space-y-2 text-sm">

                    <h1 class="font-semibold text-gray-900 leading-snug line-clamp-2">
                        {{ $item->judul }}
                    </h1>

                    <div class="text-gray-700 text-xs">
                        <span class="font-semibold text-black">
                            Rp {{ number_format($terkumpul, 0, ',', '.') }}
                        </span>
                        dari
                        <span class="font-bold">
                            Rp {{ number_format($target, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                        <div class="bg-green-600 h-1.5 rounded-full"
                             style="width: {{ $persen }}%">
                        </div>
                    </div>

                    <div class="flex justify-between text-gray-600 text-[11px] w-full">
                        <span>{{ $jumlahDonasi }} Donasi</span>

                        <div class="flex flex-col items-end leading-tight">
                            <span class="font-medium text-gray-700">Sisa Waktu:</span>
                            <span class="text-gray-500">{{ $sisaHari }}</span>
                        </div>
                    </div>

                    <a href="{{ route('program.detail', [
                        'kategori' => strtolower($item->kategori),
                        'slug' => $item->slug
                    ]) }}"
                       class="block w-full bg-green-600 text-white text-center py-2 rounded-md text-xs font-semibold hover:bg-green-700">
                        Infaq Sekarang
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        {{-- LOAD MORE --}}
        <div class="mt-6 flex justify-center">
            <button id="loadMoreBtn"
                class="px-6 py-2 bg-green-600 text-white rounded-full text-sm font-semibold flex items-center gap-2">
                <svg id="loadingIcon"
                     class="hidden animate-spin w-4 h-4"
                     viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"
                        stroke="white" stroke-width="4"
                        fill="none" opacity="0.25"/>
                    <path d="M22 12a10 10 0 0 1-10 10"
                        stroke="white" stroke-width="4"
                        fill="none"/>
                </svg>
                <span id="btnText">Load More</span>
            </button>
        </div>

        {{-- PAGINATION (tetap ada, tapi disembunyikan UI) --}}
        <div class="hidden">
            {{ $data->links() }}
        </div>

    </div>
</section>

{{-- ================= JS LOAD MORE ================= --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const cards = document.querySelectorAll('.program-card');
    const btn = document.getElementById('loadMoreBtn');
    const loader = document.getElementById('loadingIcon');
    const text = document.getElementById('btnText');
    const grid = document.getElementById('programGrid');

    const SHOW_FIRST = 4;
    let expanded = false;

    function showInitial() {
        cards.forEach((card, index) => {
            if (index < SHOW_FIRST) {
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        });

        text.textContent = 'Load More';
        loader.classList.add('hidden');
        expanded = false;
    }

    function showAll() {
        cards.forEach(card => card.classList.remove('hidden'));
        text.textContent = 'Tutup';
        loader.classList.add('hidden');
        expanded = true;
    }

    // INIT
    showInitial();

    if (cards.length <= SHOW_FIRST) {
        btn.classList.add('hidden');
    }

    btn.addEventListener('click', () => {
        loader.classList.remove('hidden');
        text.textContent = 'Loading...';

        setTimeout(() => {
            if (!expanded) {
                showAll();
            } else {
                showInitial();
                grid.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }, 700);
    });
});
</script>


@endsection
