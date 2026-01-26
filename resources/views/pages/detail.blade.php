@extends('layouts.app')
@php
    use Illuminate\Support\Str;

    /* =========================
       PREPARE OPEN GRAPH DATA
    ========================= */

    // TITLE
    $ogTitle = $berita->judul . ' | Masjid Baiturrahman';

    // DESCRIPTION (bersih, rapi, aman)
    $ogDescription = $berita->deskripsi
        ? Str::limit(
            trim(
                preg_replace('/\s+/', ' ',
                    strip_tags($berita->deskripsi)
                )
            ),
            160
        )
        : 'Informasi dan dokumentasi kegiatan Masjid Baiturrahman.';

    // IMAGE (wajib public & fallback)
    $ogImage = $berita->fotos->isNotEmpty()
        ? asset('storage/' . $berita->fotos->first()->path)
        : asset('og/berita-default.jpg');

    // URL
    $ogUrl = url()->current();
@endphp
@section('title', $ogTitle)
@section('meta_description', $ogDescription)
@section('meta_keywords', $berita->kategori . ', berita masjid, masjid baiturrahman')
@section('meta_image', $ogImage)
@section('content')

    <!-- HERO DETAIL BERITA -->
    <section class="relative bg-gradient-to-br from-green-600 to-green-800 text-white overflow-hidden">
        <div class="container mx-auto px-6 md:px-12 py-24">

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
    </section>

    <!-- DETAIL BERITA -->
    <section class="bg-white py-16">
        <div class="container mx-auto px-6 md:px-12">
            <div class="grid md:grid-cols-4 gap-10">

                <!-- KONTEN -->
                <div class="md:col-span-3">

                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">
                        {{ $berita->judul }}
                    </h2>

                    <div class="flex flex-wrap items-center text-gray-500 text-sm gap-4 mb-6">
                        <span><i class="bi bi-person-circle"></i> {{ $berita->namamasjid }}</span>
                        <span><i class="bi bi-calendar3"></i>
                            {{ \Carbon\Carbon::parse($berita->tanggal)->translatedFormat('d F Y') }}</span>
                        <span><i class="bi bi-folder2-open"></i> {{ $berita->kategori }}</span>
                    </div>

                    {{-- FOTO UTAMA --}}
                    @if ($berita->fotos->isNotEmpty())
                        <div class="mb-8 overflow-hidden rounded-xl shadow">
                            <img alt="" src="{{ asset('storage/' . $berita->fotos->first()->path) }}"
                                class="w-full max-h-[420px] object-cover cursor-pointer open-lightbox"
                                data-src="{{ asset('storage/' . $berita->fotos->first()->path) }}">
                        </div>
                    @endif

                    {{-- DESKRIPSI --}}
                    <div class="text-gray-700 leading-relaxed text-justify space-y-4">
                        <p>
                            <strong>{{ \Carbon\Carbon::parse($berita->tanggal)->translatedFormat('d F Y') }}</strong>
                            – {!! nl2br(e($berita->deskripsi)) !!}
                        </p>
                    </div>

                    {{-- GALERI --}}
                    @if ($berita->fotos->count() > 1)
                        <div class="mt-10">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Dokumentasi Kegiatan</h3>

                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach ($berita->fotos->skip(1) as $foto)
                                    <img src="{{ asset('storage/' . $foto->path) }}" alt=""
                                        data-src="{{ asset('storage/' . $foto->path) }}"
                                        class="w-full h-48 object-cover rounded-lg shadow cursor-pointer hover:scale-105 transition open-lightbox">
                                @endforeach
                            </div>
                        </div>
                    @endif
                    {{-- SHARE --}}
                    <div class="mt-10 border-t pt-6 flex items-center space-x-4 text-gray-600">
                        <span>Bagikan:</span>

                        {{-- WhatsApp --}}
                        <a href="https://wa.me/?text={{ urlencode($ogTitle . ' - ' . $ogUrl) }}"
                        target="_blank"
                        class="text-green-600 hover:text-green-800">
                            <i class="bi bi-whatsapp text-xl"></i>
                        </a>

                        {{-- Facebook --}}
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($ogUrl) }}"
                        target="_blank"
                        class="text-green-600 hover:text-green-800">
                            <i class="bi bi-facebook text-xl"></i>
                        </a>

                        {{-- Twitter --}}
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode($ogUrl) }}"
                        target="_blank"
                        class="text-green-600 hover:text-green-800">
                            <i class="bi bi-twitter text-xl"></i>
                        </a>

                        {{-- Email --}}
                        <a href="mailto:?subject={{ urlencode($ogTitle) }}&body={{ urlencode($ogUrl) }}"
                        class="text-green-600 hover:text-green-800">
                            <i class="bi bi-envelope-fill text-xl"></i>
                        </a>
                    </div>

                </div>

                <!-- SIDEBAR -->
                <div>
                    <div class="bg-white shadow rounded-xl p-6">
                        <h3 class="font-bold text-green-700 mb-4">Berita Lainnya</h3>
                            <ul class="space-y-3 text-sm">
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

    <!-- LIGHTBOX -->
    <div id="lightbox" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 backdrop-blur-sm">
        <button id="closeLightbox" class="absolute top-6 right-6 text-white text-3xl font-bold hover:text-red-400">
            &times;
        </button>
        <img id="lightboxImage" class="max-w-[90%] max-h-[85%] rounded-xl shadow-2xl object-contain" alt="">
    </div>

    <!-- SCRIPT -->
    <script>
        document.querySelectorAll('.open-lightbox').forEach(img => {
            img.addEventListener('click', () => {
                document.getElementById('lightboxImage').src = img.dataset.src;
                document.getElementById('lightbox').classList.remove('hidden');
                document.getElementById('lightbox').classList.add('flex');
            });
        });

        document.getElementById('closeLightbox').onclick = closeLightbox;
        document.getElementById('lightbox').onclick = e => {
            if (e.target.id === 'lightbox') closeLightbox();
        };

        function closeLightbox() {
            const lb = document.getElementById('lightbox');
            lb.classList.add('hidden');
            lb.classList.remove('flex');
        }
    </script>

@endsection
