@extends('layouts.app')

@section('title', 'Berita & Kegiatan - Masjid Baiturrahman')

@section('content')
<!-- Hero Section -->
<div class="bg-green-50 py-16 relative overflow-hidden">
  <div class="container mx-auto px-6 md:px-12">
    <h1 class="text-3xl font-bold text-gray-800">Berita & Kegiatan</h1>
    <p class="text-gray-600 mt-2">Kumpulan berita dan kegiatan terbaru Masjid Baiturrahman</p>
  </div>
</div>

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
            @if ($item->foto)
              <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->judul }}" class="w-full h-52 object-cover transition-transform duration-500 group-hover:scale-105">
            @else
              <img src="{{ asset('build/assets/masjid.jpeg') }}" alt="default" class="w-full h-52 object-cover transition-transform duration-500 group-hover:scale-105">
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
