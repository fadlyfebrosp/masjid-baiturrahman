@extends('layouts.app')

@section('title', 'Program ' . ($kategori ?? 'Donasi') . ' | Masjid Baiturrahman')

@section('content')

<!-- ================= HERO + BREADCRUMB ================= -->
<div class="bg-green-50 py-14">
    <div class="container mx-auto px-6 md:px-12">

        <nav class="mb-3 text-sm">
            <ol class="flex items-center gap-2 text-gray-600">
                <li>
                    <a href="{{ url('/') }}" class="text-green-700 hover:text-green-800 font-medium">
                        Beranda
                    </a>
                </li>
                <li class="text-gray-400">›</li>
                <li class="font-semibold text-gray-800">
                    Program {{ $kategori ?? 'Donasi' }}
                </li>
            </ol>
        </nav>

        <h1 class="text-3xl font-bold text-gray-800">
            Program {{ $kategori ?? 'Donasi' }}
        </h1>

        <p class="text-gray-600 mt-2">
            Program donasi {{ strtolower($kategori ?? 'masjid') }} Masjid Baiturrahman
        </p>

    </div>
</div>

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
