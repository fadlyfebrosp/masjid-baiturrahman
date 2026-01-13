@extends('layouts.app')

@section('title', 'Program ' . ($kategori ?? 'Donasi') . ' | Masjid Baiturrahman')

@section('content')

<!-- ================= HERO PROGRAM DONASI ================= -->
<section class="relative bg-gradient-to-br from-green-600 to-green-800 text-white overflow-hidden">
    <div class="container mx-auto px-6 md:px-12 py-24">

        <!-- Breadcrumb -->
        <nav class="text-sm opacity-90 mb-3">
            <a href="{{ url('/') }}" class="hover:underline">
                Beranda
            </a>
            <span class="mx-2">/</span>
            <span class="font-medium">
                Program {{ $kategori ?? 'Donasi' }}
            </span>
        </nav>

        <!-- Title -->
        <h1 class="text-4xl md:text-5xl font-bold leading-tight">
            Program {{ $kategori ?? 'Donasi' }}
        </h1>

        <!-- Subtitle -->
        <p class="mt-4 max-w-2xl text-lg text-green-100">
            Program donasi {{ strtolower($kategori ?? 'masjid') }} Masjid Baiturrahman
        </p>

    </div>

    <!-- Decorative blur -->
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-green-400 opacity-20 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-green-300 opacity-20 rounded-full blur-3xl"></div>
</section>

<!-- ================= FILTER KATEGORI ================= -->
<div class="container mx-auto px-6 md:px-12 mt-8">
    <form method="POST" action="{{ route('pages.program') }}"
          class="flex flex-wrap gap-3 justify-center">
        @csrf

        @foreach (['zakat','infaq','sedekah','wakaf','hibah'] as $kat)
            <button type="submit"
                    name="kategori"
                    value="{{ $kat }}"
                    class="px-5 py-2 rounded-full text-sm font-medium border transition
                    {{ strtolower($kategori ?? '') === $kat
                        ? 'bg-green-600 text-white border-green-600'
                        : 'border-gray-300 text-gray-700 hover:bg-green-600 hover:text-white' }}">
                {{ ucfirst($kat) }}
            </button>
        @endforeach
    </form>
</div>

<!-- ================= FILTER SUB ZAKAT ================= -->
@if (strtolower($kategori ?? '') === 'zakat')
<div class="container mx-auto px-6 md:px-12 mt-6">
    <form method="POST" action="{{ route('pages.program') }}"
          class="flex flex-wrap gap-3 justify-center">
        @csrf
        <input type="hidden" name="kategori" value="zakat">

        <button type="submit"
                class="px-5 py-2 rounded-full text-sm font-medium border
                {{ empty(request('sub'))
                    ? 'bg-green-600 text-white border-green-600'
                    : 'border-gray-300 text-gray-700 hover:bg-green-600 hover:text-white' }}">
            Semua Zakat
        </button>

        @foreach ($subZakat as $key => $label)
            <button type="submit"
                    name="sub"
                    value="{{ $key }}"
                    class="px-5 py-2 rounded-full text-sm font-medium border transition
                    {{ request('sub') === $key
                        ? 'bg-green-600 text-white border-green-600'
                        : 'border-gray-300 text-gray-700 hover:bg-green-600 hover:text-white' }}">
                {{ $label }}
            </button>
        @endforeach
    </form>
</div>
@endif

<!-- ================= LIST PROGRAM ================= -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-6 md:px-12">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

            @forelse ($data as $program)
            @php
                $terkumpul    = $program->terkumpul ?? 0;
                $target       = max(1, $program->target_dana ?? 1);
                $persen       = min(100, ($terkumpul / $target) * 100);
                $jumlahDonasi = $program->jumlah_donasi ?? 0;
                $sisaHari     = $program->sisa_hari ?? '—';
            @endphp

            <div class="program-card group bg-white rounded-2xl border shadow-sm hover:shadow-xl transition overflow-hidden">

                <!-- IMAGE -->
                <div class="relative">
                    <img
                        src="{{ $program->foto
                            ? asset('storage/'.$program->foto)
                            : asset('assets/img/program/default.jpg') }}"
                        alt="{{ $program->judul }}"
                        class="w-full h-48 sm:h-40 md:h-44 object-cover group-hover:scale-105 transition">

                    <span class="absolute top-3 left-3 bg-green-600 text-white text-[10px] px-3 py-1 rounded-full font-semibold">
                        {{ ucfirst($program->kategori) }}
                    </span>
                </div>

                <!-- CONTENT -->
                <div class="p-4 space-y-3">

                    <h3 class="font-semibold text-gray-900 line-clamp-2 text-sm sm:text-base min-h-[48px]">
                        {{ $program->judul }}
                    </h3>

                    @if (!empty($program->sub_kategori))
                        <p class="text-xs text-gray-500 line-clamp-1">
                            {{ $program->sub_kategori }}
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
                            <div class="bg-green-600 h-2 rounded-full"
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
                            'kategori' => strtolower($program->kategori),
                            'slug'     => $program->slug
                        ]) }}"
                       class="block w-full bg-green-600 text-white text-center py-2.5 rounded-xl text-sm font-semibold hover:bg-green-700 transition">
                        Donasi Sekarang
                    </a>

                </div>
            </div>

            @empty
                <p class="text-center text-gray-500 col-span-3">
                    Program belum tersedia.
                </p>
            @endforelse

        </div>

        <!-- PAGINATION -->
        @if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="mt-12">
            {{ $data->links('pagination::tailwind') }}
        </div>
        @endif

    </div>
</section>

@endsection
