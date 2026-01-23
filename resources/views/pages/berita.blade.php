@extends('layouts.app')

@section('title', 'Berita & Kegiatan | Masjid Baiturrahman')

@section('meta_description')
Berita dan kegiatan terbaru Masjid Baiturrahman, meliputi kajian, sosial, dan aktivitas keislaman.
@endsection

@section('meta_keywords')
berita masjid, kegiatan masjid, kajian islam, masjid baiturrahman
@endsection

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-green-600 to-green-800 text-white overflow-hidden">
    <div class="container mx-auto px-6 md:px-12 py-24">

        <!-- Breadcrumb -->
        <nav class="text-sm mb-4 opacity-90">
            <a href="{{ url('/') }}" class="hover:underline">Beranda</a>
            <span class="mx-2">/</span>
            <span class="font-semibold">Berita & Kegiatan</span>
        </nav>

        <!-- Title -->
        <h1 class="text-4xl md:text-5xl font-bold leading-tight">
            Berita & Kegiatan
        </h1>

        <!-- Subtitle -->
        <p class="mt-5 max-w-2xl text-lg text-green-100">
            Informasi terbaru seputar kegiatan, dakwah, dan aktivitas
            Masjid Baiturrahman dalam melayani umat.
        </p>

    </div>

    <!-- Decorative Blur -->
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-green-400 opacity-20 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-green-300 opacity-20 rounded-full blur-3xl"></div>
</section>

<!-- Filter Kategori -->
<div class="flex flex-wrap justify-center gap-3 mt-8">
<button class="filter-btn px-4 py-2 rounded-full border border-green-600 bg-green-600 text-white text-sm font-medium hover:bg-green-700" data-filter="Semua">Semua</button>
<button class="filter-btn px-4 py-2 rounded-full border border-gray-300 text-gray-700 text-sm font-medium hover:bg-green-700" data-filter="Berita">Berita</button>
<button class="filter-btn px-4 py-2 rounded-full border border-gray-300 text-gray-700 text-sm font-medium hover:bg-green-700" data-filter="Kegiatan">Kegiatan</button>
</div>

<!-- Semua Berita & Kegiatan -->
<section class="py-16 bg-white">
<div class="container mx-auto px-6 md:px-12 space-y-12">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
    @forelse ($data as $item)
        <div class="bg-white border border-green-400 rounded-2xl shadow hover:shadow-md transition overflow-hidden item-card" data-category="{{ $item->kategori }}">
        <div class="relative group">
            @if ($item->fotos->isNotEmpty())
                <img
                    src="{{ asset('storage/' . $item->fotos->first()->path) }}"
                    alt="{{ $item->judul }}"
                    class="w-full h-52 object-cover transition-transform duration-500 group-hover:scale-105">
            @else
                <img
                    src="{{ asset('build/assets/masjid.jpeg') }}"
                    alt="default"
                    class="w-full h-52 object-cover transition-transform duration-500 group-hover:scale-105">
            @endif
            <span class="absolute top-2 left-2 bg-green-600 text-white text-xs px-3 py-1 rounded-md uppercase tracking-wide">
            {{ $item->kategori }}
            </span>
        </div>
        <div class="p-4 text-left">
            <p class="text-gray-500 text-sm mb-2 flex items-center gap-1">
            <i class="bi bi-calendar-event text-green-600"></i> {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
            </p>
            <h5 class="text-lg font-semibold text-gray-800 mb-2">
                {{ $item->judul }}
            </h5>
            <p class="text-gray-600 text-sm mb-3 line-clamp-3">
                {{ $item->deskripsi }}
            </p>
            <a href="{{ url('/berita/' . urlencode($item->judul)) }}"
                class="text-green-600 font-semibold hover:underline text-sm">
                Baca Selengkapnya
            </a>
        </div>
        </div>
    @empty
        <p class="text-center text-gray-500 col-span-3">Belum ada berita atau kegiatan.</p>
    @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-10">
    {{ $data->links('pagination::tailwind') }}
    </div>
</div>
</section>

<script>
const buttons = document.querySelectorAll(".filter-btn");
const items = document.querySelectorAll(".item-card");

buttons.forEach(btn => {
    btn.addEventListener("click", () => {
    const filter = btn.dataset.filter;

    buttons.forEach(b => b.classList.remove("bg-green-600", "text-white"));
    btn.classList.add("bg-green-600", "text-white");

    items.forEach(item => {
        if (filter === "Semua" || item.dataset.category === filter) {
        item.classList.remove("hidden");
        } else {
        item.classList.add("hidden");
        }
    });
    });
});
</script>
@endsection
