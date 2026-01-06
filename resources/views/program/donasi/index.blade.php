@extends('layouts.app')

@section('title', $kategori . ' | Program Donasi Masjid Baiturrahman')

@section('meta_description')
Daftar program {{ strtolower($kategori) }} Masjid Baiturrahman. Salurkan donasi, infaq, dan zakat ke program resmi, aman, dan terverifikasi.
@endsection

@section('meta_keywords')
{{ strtolower($kategori) }}, donasi {{ strtolower($kategori) }}, zakat online, infaq masjid, masjid baiturrahman
@endsection

@section('meta_image', asset('assets/img/logo1.png'))

@section('content')
{{-- ================= HEADER ================= --}}
<div class="bg-green-50 py-10 sm:py-12 relative overflow-hidden">
  <div class="container mx-auto px-4 sm:px-6">

    <!-- Breadcrumb -->
    <nav class="mb-3" aria-label="Breadcrumb">
      <ol class="flex flex-wrap items-center text-sm text-gray-600 gap-2">

        <li>
          <a href="{{ url('/') }}"
             class="text-green-700 hover:text-green-800 font-medium transition">
             <i class="bi bi-house-door-fill"></i>
            Beranda
          </a>
        </li>

        <li class="text-gray-400">›</li>

        <li>
          <a href="{{ url('/program') }}"
             class="text-green-700 hover:text-green-800 font-medium transition">
            Program
          </a>
        </li>

        <li class="text-gray-400">›</li>

        <li class="text-gray-800 font-semibold">
          {{ $kategori }}
        </li>

      </ol>
    </nav>

    <!-- Title -->
    <h1 class="text-xl sm:text-2xl font-bold text-gray-800">
      Program <span class="text-green-700">/ {{ $kategori }}</span>
    </h1>

  </div>
</div>
{{-- ================= FILTER ZAKAT ================= --}}
@if (strtolower($kategori) === 'zakat')
<div class="bg-white border-b">
    <div class="container mx-auto px-4 py-4">
        <p class="text-sm font-semibold text-gray-700 mb-3">
            Pilih Jenis Zakat
        </p>

        <div class="flex gap-2 overflow-x-auto -mx-4 px-4 pb-2">

            {{-- SEMUA --}}
            <a href="{{ route('program.index', ['kategori' => 'zakat']) }}"
               class="shrink-0 px-4 py-2 text-xs sm:text-sm rounded-full border font-semibold transition
               {{ request()->missing('sub')
                    ? 'bg-green-600 text-white shadow'
                    : 'bg-white text-gray-700 hover:bg-green-50'
               }}">
                Semua
            </a>

            {{-- SUB ZAKAT --}}
            @foreach ($subZakat as $key => $label)
                <a href="{{ route('program.index', [
                        'kategori' => 'zakat',
                        'sub' => $key
                    ]) }}"
                   class="shrink-0 px-4 py-2 text-xs sm:text-sm rounded-full border font-semibold transition
                   {{ request('sub') === $key
                        ? 'bg-green-600 text-white shadow'
                        : 'bg-white text-gray-700 hover:bg-green-50'
                   }}">
                    {{ $label }}
                </a>
            @endforeach

        </div>
    </div>
</div>
@endif


{{-- ================= LIST PROGRAM ================= --}}
<section class="bg-white py-6">
    <div class="container mx-auto px-4">

        <div class="mb-4">
            <h2 class="text-base font-semibold text-gray-800">
                {{ $kategori }} ke program spesifik
            </h2>
            <p class="text-xs text-gray-600">
                Salurkan {{ strtolower($kategori) }} ke program terverifikasi
            </p>
        </div>

        <p class="text-sm font-semibold text-orange-600 mb-3">
            REKOMENDASI
        </p>

        {{-- GRID --}}
        <div
            id="programGrid"
            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6"
        >
            @foreach ($data as $item)
            @php
                $terkumpul = $item->terkumpul ?? 0;
                $target = $item->target_dana && $item->target_dana > 0 ? $item->target_dana : 1;
                $persen = min(100, ($terkumpul / $target) * 100);
                $jumlahDonasi = $item->jumlah_donasi ?? 0;

                if ($item->open_goals) {
                    $sisaHari = 'Tanpa Batas Waktu';
                } elseif ($item->target_waktu) {
                    $hari = now()->startOfDay()->diffInDays(
                        \Carbon\Carbon::parse($item->target_waktu)->startOfDay(),
                        false
                    );
                    $sisaHari = $hari > 0 ? $hari . ' Hari' : 'Berakhir';
                } else {
                    $sisaHari = 'Belum diatur';
                }
            @endphp

            {{-- CARD --}}
            <div class="program-card hidden group bg-white rounded-2xl border shadow-sm hover:shadow-xl transition overflow-hidden">

                <div class="relative">
                    <img
                        src="{{ $item->foto ? asset('storage/' . $item->foto) : asset('assets/img/program/default.jpg') }}"
                        alt="{{ $item->judul }}"
                        class="w-full h-48 sm:h-40 md:h-44 object-cover group-hover:scale-105 transition">

                    <span class="absolute top-3 left-3 bg-green-600 text-white text-[10px] px-3 py-1 rounded-full font-semibold">
                        {{ $item->kategori }}
                    </span>
                </div>

                <div class="p-4 space-y-3">
                    <h3 class="font-semibold text-gray-900 line-clamp-2 text-sm sm:text-base min-h-[48px]">
                        {{ $item->judul }}
                    </h3>
                    @if(!empty($item->sub_kategori))
                        <p class="text-xs text-gray-500 line-clamp-1">
                            {{ $item->sub_kategori ?? 'Program Donasi' }}
                        </p>
                    @endif

                    <div class="text-xs text-gray-600">
                        <span class="text-base font-bold text-gray-900">
                            Rp {{ number_format($terkumpul, 0, ',', '.') }}
                        </span>
                        /
                        <span class="font-semibold">
                            Rp {{ number_format($target, 0, ',', '.') }}
                        </span>
                    </div>

                    <div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div
                                class="bg-green-600 h-2 rounded-full"
                                style="width: {{ $persen }}%">
                            </div>
                        </div>

                        <div class="flex justify-between text-[11px] text-gray-500 mt-1">
                            <span>{{ $persen }}%</span>
                            <span>{{ $jumlahDonasi }} donasi</span>
                        </div>
                    </div>

                    <div class="flex justify-between text-xs text-gray-600">
                        <span>⏳ {{ $sisaHari }}</span>
                        <span class="text-green-600 font-semibold">Terverifikasi</span>
                    </div>

                    <a href="{{ route('program.detail', [
                        'kategori' => strtolower($item->kategori),
                        'slug' => $item->slug
                    ]) }}"
                       class="block w-full bg-green-600 text-white text-center py-2.5 rounded-xl text-sm font-semibold hover:bg-green-700">
                        Infaq Sekarang
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        {{-- LOAD MORE --}}
        <div class="mt-6 flex justify-center">
            <button
                id="loadMoreBtn"
                class="px-6 py-2 bg-green-600 text-white rounded-full text-sm font-semibold flex items-center gap-2">
                <svg id="loadingIcon" class="hidden animate-spin w-4 h-4" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" stroke="white" stroke-width="4" fill="none" opacity="0.25"/>
                    <path d="M22 12a10 10 0 0 1-10 10" stroke="white" stroke-width="4" fill="none"/>
                </svg>
                <span id="btnText">Load More</span>
            </button>
        </div>

    </div>
</section>

{{-- ================= JS LOAD MORE ================= --}}
<script>
document.addEventListener('DOMContentLoaded', () => {

    const cards = [...document.querySelectorAll('.program-card')];
    const btn = document.getElementById('loadMoreBtn');
    const loader = document.getElementById('loadingIcon');
    const text = document.getElementById('btnText');
    const grid = document.getElementById('programGrid');

    let expanded = false;

    function getLimit() {
        if (window.innerWidth < 640) return 3;
        if (window.innerWidth < 1024) return 6;
        return 8;
    }

    let LIMIT = getLimit();

    function render() {
        cards.forEach((card, i) => {
            card.classList.toggle('hidden', !expanded && i >= LIMIT);
        });

        btn.classList.toggle('hidden', cards.length <= LIMIT);
        text.textContent = expanded ? 'Tutup' : 'Load More';
        loader.classList.add('hidden');
    }

    render();

    btn.addEventListener('click', () => {
        loader.classList.remove('hidden');
        text.textContent = 'Loading...';

        setTimeout(() => {
            expanded = !expanded;
            render();
            if (!expanded) grid.scrollIntoView({ behavior: 'smooth' });
        }, 500);
    });

    window.addEventListener('resize', () => {
        LIMIT = getLimit();
        expanded = false;
        render();
    });

});
</script>

@endsection
