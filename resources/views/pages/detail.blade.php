@extends('layouts.app')

@section('title', $berita->judul . ' | Masjid Baiturrahman')

@section('meta_description', Str::limit(strip_tags($berita->deskripsi), 155))

@section('meta_keywords', $berita->kategori . ', berita masjid, masjid baiturrahman')

@section('meta_image',
    $berita->fotos->isNotEmpty()
        ? asset('storage/'.$berita->fotos->first()->path)
        : asset('build/assets/masjid.jpeg')
)

@section('content')

<!-- HERO DETAIL BERITA -->
<section class="relative bg-gradient-to-br from-green-600 to-green-800 text-white overflow-hidden">
    <div class="container mx-auto px-6 md:px-12 py-24">

        <!-- Breadcrumb -->
        <nav class="text-sm mb-4 opacity-90">
            <a href="{{ url('/') }}" class="hover:underline">Beranda</a>
            <span class="mx-2">/</span>
            <a href="{{ route('berita') }}" class="hover:underline">Berita & Kegiatan</a>
            <span class="mx-2">/</span>
            <span class="font-semibold">{{ $berita->kategori }}</span>
        </nav>

        <h1 class="text-4xl md:text-5xl font-bold leading-tight">
            {{ $berita->kategori }}
        </h1>

        <p class="mt-5 max-w-2xl text-lg text-green-100">
            Informasi dan dokumentasi kegiatan Masjid Baiturrahman
            dalam kategori <span class="font-semibold">{{ $berita->kategori }}</span>.
        </p>

    </div>

    <div class="absolute -top-24 -right-24 w-96 h-96 bg-green-400 opacity-20 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-green-300 opacity-20 rounded-full blur-3xl"></div>
</section>

<!-- DETAIL BERITA -->
<section class="bg-white py-16">
    <div class="container mx-auto px-6 md:px-12">
        <div class="grid md:grid-cols-4 gap-10">

            <!-- KONTEN UTAMA -->
            <div class="md:col-span-3">

                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2 leading-snug">
                    {{ $berita->judul }}
                </h2>

                <div class="flex flex-wrap items-center text-gray-500 text-sm gap-x-4 gap-y-2 mb-6">
                    <span><i class="bi bi-person-circle"></i> {{ $berita->namamasjid }}</span>
                    <span><i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::parse($berita->tanggal)->translatedFormat('d F Y') }}</span>
                    <span><i class="bi bi-folder2-open"></i> {{ $berita->kategori }}</span>
                </div>

                {{-- FOTO UTAMA --}}
                @if ($berita->fotos->isNotEmpty())
                    <div class="mb-8 overflow-hidden rounded-xl shadow">
                        <img
                            src="{{ asset('storage/'.$berita->fotos->first()->path) }}"
                            alt="{{ $berita->judul }}"
                            class="w-full max-h-[420px] object-cover">
                    </div>
                @endif
                {{-- DESKRIPSI --}}
                <div class="space-y-5 text-gray-700 leading-relaxed text-justify">
                    <p>
                        <strong>
                            {{ \Carbon\Carbon::parse($berita->tanggal)->translatedFormat('d F Y') }}
                        </strong>
                        –
                        {!! nl2br(e($berita->deskripsi)) !!}
                    </p>
                </div>

                {{-- GALERI FOTO TAMBAHAN --}}
                @if ($berita->fotos->count() > 1)
                    <div class="mt-10">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Dokumentasi Kegiatan</h3>

                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach ($berita->fotos->skip(1) as $foto)
                                <div class="overflow-hidden rounded-lg shadow">
                                    <img
                                        src="{{ asset('storage/'.$foto->path) }}"
                                        alt="{{ $berita->judul }}"
                                        class="w-full h-48 object-cover hover:scale-105 transition duration-300">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- SHARE --}}
                <div class="mt-10 border-t pt-6 flex items-center space-x-4 text-gray-600">
                    <span>Bagikan:</span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank" class="text-green-600 hover:text-green-800">
                        <i class="bi bi-facebook text-xl"></i>
                    </a>
                    <a href="https://wa.me/?text={{ urlencode(request()->fullUrl()) }}" target="_blank" class="text-green-600 hover:text-green-800">
                        <i class="bi bi-whatsapp text-xl"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}" target="_blank" class="text-green-600 hover:text-green-800">
                        <i class="bi bi-twitter text-xl"></i>
                    </a>
                    <a href="mailto:?subject={{ urlencode($berita->judul) }}&body={{ urlencode(request()->fullUrl()) }}" class="text-green-600 hover:text-green-800">
                        <i class="bi bi-envelope-fill text-xl"></i>
                    </a>
                </div>

            </div>

            <!-- SIDEBAR -->
            <div class="space-y-5">
                <div class="bg-white shadow rounded-xl p-6">
                    <h3 class="font-bold text-green-700 mb-4">Berita Lainnya</h3>
                    <ul class="space-y-3 text-gray-700 text-sm">
                        @foreach ($beritaLainnya as $lainnya)
                            <li>
                                <a href="{{ url('/berita/' . urlencode($lainnya->judul)) }}"
                                   class="hover:text-green-600">
                                    {{ $lainnya->judul }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection
