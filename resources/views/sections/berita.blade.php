<section class="py-16 bg-white text-center">
  <div class="max-w-6xl mx-auto px-3">
    <div class="text-center mb-8">
      <h2 class="text-3xl font-bold">Berita & Kegiatan</h2>
      <p class="text-gray-600">Berita dan kegiatan terbaru Masjid Baiturrahman</p>
    </div>

    <!-- Filter Kategori -->
    <div class="flex flex-wrap justify-center gap-3 mb-10">
      <button class="filter-btn px-4 py-2 rounded-full border border-green-600 bg-green-700 text-white text-sm font-medium hover:bg-green-700" data-filter="Semua">Semua</button>
      <button class="filter-btn px-4 py-2 rounded-full border border-gray-300 text-gray-700 text-sm font-medium hover:bg-green-700" data-filter="Berita">Berita</button>
      <button class="filter-btn px-4 py-2 rounded-full border border-gray-300 text-gray-700 text-sm font-medium hover:bg-green-700" data-filter="Kegiatan">Kegiatan</button>
    </div>

    <div class="relative">
      <!-- Tombol kiri -->
      <button id="scrollLeft" class="absolute left-0 top-1/2 -translate-y-1/2 bg-white shadow-md border rounded-full p-2 hover:bg-green-100 z-10">
        <i class="bi bi-chevron-left text-green-600 text-lg"></i>
      </button>

      <!-- Carousel -->
      <div id="carousel" class="flex gap-6 overflow-x-auto scroll-smooth snap-x snap-mandatory no-scrollbar px-8">
        @forelse ($data as $item)
          <div class="snap-center flex-shrink-0 w-[104%] sm:w-[49%] lg:w-[33%] bg-white border border-green-400 rounded-2xl shadow hover:shadow-md transition overflow-hidden item-card" data-category="{{ $item->kategori }}">
            <div class="relative group">
              @if ($item->foto)
                <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->judul }}" class="w-full h-52 object-cover transition-transform duration-500 group-hover:scale-105">
              @else
                <img src="{{ asset('build/assets/masjid.jpeg') }}" alt="default" class="w-full h-52 object-cover transition-transform duration-500 group-hover:scale-105">
              @endif
              <span class="absolute top-2 left-2 bg-green-600 text-white text-xs px-3 py-1 rounded-md uppercase tracking-wide">{{ $item->kategori }}</span>
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
              <a href="{{ url('/berita/' . urlencode($item->judul)) }}" class="text-green-600 font-semibold hover:underline text-sm">Baca Selengkapnya</a>
            </div>
          </div>
        @empty
          <p class="text-center text-gray-500 col-span-3 w-full">Belum ada berita atau kegiatan.</p>
        @endforelse
      </div>

      <!-- Tombol kanan -->
      <button id="scrollRight" class="absolute right-0 top-1/2 -translate-y-1/2 bg-white shadow-md border rounded-full p-2 hover:bg-green-100 z-10">
        <i class="bi bi-chevron-right text-green-600 text-lg"></i>
      </button>

      <!-- Tombol "Lihat Berita Lainnya" -->
      <div class="flex justify-center mt-12">
        <a href="/beritadankegiatan" class="boton-elegante flex items-center gap-2">
          Lihat Berita Lainnya
          <i class="bi bi-chevron-right text-white text-lg transition-transform duration-300 group-hover:translate-x-1"></i>
        </a>
      </div>
    </div>
  </div>
</section>

<script>
  // ============================
  // SCROLL KIRI - KANAN
  // ============================
  const carousel = document.getElementById("carousel");

  document.getElementById("scrollLeft").addEventListener("click", () => {
    carousel.scrollBy({ left: -300, behavior: "smooth" });
  });

  document.getElementById("scrollRight").addEventListener("click", () => {
    carousel.scrollBy({ left: 300, behavior: "smooth" });
  });

  // ============================
  // FILTER KATEGORI
  // ============================
  const buttons = document.querySelectorAll(".filter-btn");
  const items = document.querySelectorAll(".item-card");

  buttons.forEach(btn => {
    btn.addEventListener("click", () => {
      const filter = btn.dataset.filter;

      // Hapus style aktif dari semua button
      buttons.forEach(b => {
        b.classList.remove("bg-green-600", "text-white", "border-green-600");
        b.classList.add("bg-white", "text-gray-700", "border-gray-300");
      });

      // Tambahkan style ke button yang diklik
      btn.classList.add("bg-green-600", "text-white", "border-green-600");
      btn.classList.remove("bg-white", "text-gray-700", "border-gray-300");

      // Filter item
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

