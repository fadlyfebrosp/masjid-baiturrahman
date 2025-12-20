<style>
.scroll-animate {
    opacity: 0;
    transform: translateY(40px);
    transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
}

.from-top {
    transform: translateY(-40px);
}

.from-bottom {
    transform: translateY(40px);
}

.scroll-animate.active {
    opacity: 1;
    transform: translate(0, 0);
}

/* Delay animasi */
.delay-0 { transition-delay: 0s; }
.delay-1 { transition-delay: .1s; }
.delay-2 { transition-delay: .2s; }
.delay-3 { transition-delay: .3s; }
.delay-4 { transition-delay: .4s; }
.delay-5 { transition-delay: .5s; }
</style>
<section class="py-16 bg-white text-center">
  <div class="max-w-6xl mx-auto px-3">

    <!-- ===================== -->
    <!--        JUDUL          -->
    <!-- ===================== -->
    <div class="text-center mb-8 scroll-animate from-top">
      <h2 class="text-3xl font-bold">Program Masjid</h2>
      <p class="text-gray-600">Program Zakat, Infak, Sedekah dan Wakaf</p>
    </div>

    <!-- ===================== -->
    <!--   FILTER KATEGORI     -->
    <!-- ===================== -->
    <div class="flex flex-wrap justify-center gap-3 mb-10">
      @php
        $kategoriList = ["Semua", "Zakat", "Infak", "Sedekah", "Wakaf"];
      @endphp

      @foreach ($kategoriList as $kategori)
        <button
          class="filter-program px-4 py-2 rounded-full border border-gray-300 text-gray-700 text-sm font-medium
                 scroll-animate from-bottom delay-{{ $loop->index }}"
          data-filter="{{ $kategori }}">
          {{ $kategori }}
        </button>
      @endforeach
    </div>

    <!-- ===================== -->
    <!--       CAROUSEL        -->
    <!-- ===================== -->
    <div class="relative scroll-animate from-bottom">

      <!-- Tombol kiri -->
      <button id="scrollProgramLeft"
        class="absolute left-0 top-1/2 -translate-y-1/2 bg-white shadow-md border rounded-full p-2 hover:bg-green-100 z-10">
        <i class="bi bi-chevron-left text-green-600 text-lg"></i>
      </button>

      <!-- Wrapper Carousel -->
      <div id="programCarousel"
        class="flex gap-6 overflow-x-auto scroll-smooth snap-x snap-mandatory no-scrollbar px-8">

        @forelse ($programs as $item)

          @php
            $terkumpul = $item->terkumpul ?? 0;
            $target = $item->target_dana ?? 1;
            $persen = min(100, ($terkumpul / $target) * 100);

            if ($item->open_goals) {
                $sisaHari = "Tanpa Batas Waktu";
            } else {
                if ($item->target_waktu) {
                    $sisa = now()->startOfDay()->diffInDays(
                        \Carbon\Carbon::parse($item->target_waktu)->startOfDay(),
                        false
                    );
                    $sisaHari = $sisa > 0 ? ceil($sisa) . " hari lagi" : "Berakhir";
                } else {
                    $sisaHari = "Belum diatur";
                }
            }
          @endphp

          <div
            class="program-card snap-center flex-shrink-0
                   w-[104%] sm:w-[49%] lg:w-[33%]
                   bg-white border border-green-400 rounded-2xl shadow hover:shadow-md transition overflow-hidden
                   scroll-animate from-bottom delay-{{ $loop->index }}"
            data-category="{{ $item->kategori }}">

            <!-- FOTO -->
            <div class="relative group">
              <img
                src="{{ $item->foto ? asset('storage/' . $item->foto) : asset('build/assets/masjid.jpeg') }}"
                alt="{{ $item->judul }}"
                class="w-full h-52 object-cover transition-transform duration-500 group-hover:scale-105">

              <span
                class="absolute top-2 left-2 bg-green-600 text-white text-xs px-3 py-1 rounded-md uppercase">
                {{ $item->kategori }}
              </span>
            </div>

            <!-- KONTEN -->
            <div class="p-6 space-y-4 text-left">
              <h1 class="text-xl font-bold text-gray-900">
                {{ $item->judul }}
              </h1>
              @if ($item->kategori === 'Zakat' && $item->sub_kategori)
                    <p class="text-sm font-medium text-green-700">
                        {{ $item->sub_kategori_label }}
                    </p>
                @endif

              <div class="text-gray-700">
                <span class="font-semibold text-black">
                  Rp {{ number_format($terkumpul, 0, ',', '.') }}
                </span>
                dari
                <span class="font-bold">
                  Rp {{ number_format($target, 0, ',', '.') }}
                </span>
              </div>

              <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-green-600 h-2 rounded-full"
                     style="width: {{ $persen }}%"></div>
              </div>

              <div class="flex justify-between text-sm text-gray-600">
                <span>{{ $item->jumlah_donasi ?? 0 }} Donasi</span>
                <div class="text-right">
                  <div class="font-medium text-gray-700">Sisa Waktu</div>
                  <div class="text-gray-500">{{ $sisaHari }}</div>
                </div>
              </div>

              <a href="{{ route('program.detail', [
                    'kategori' => strtolower($item->kategori),
                    'slug' => $item->slug
                ]) }}"
                 class="block w-full bg-green-600 text-white text-center py-3 rounded-lg font-semibold hover:bg-green-700">
                Infaq Sekarang!
              </a>
            </div>
          </div>

        @empty
          <p class="text-center text-gray-500 w-full">Belum ada program.</p>
        @endforelse

      </div>

      <!-- Tombol kanan -->
      <button id="scrollProgramRight"
        class="absolute right-0 top-1/2 -translate-y-1/2 bg-white shadow-md border rounded-full p-2 hover:bg-green-100 z-10">
        <i class="bi bi-chevron-right text-green-600 text-lg"></i>
      </button>

    </div>
  </div>
</section>
<script>
document.addEventListener("DOMContentLoaded", () => {

  /* =========================
     SCROLL ANIMATION
  ========================= */
  const animatedItems = document.querySelectorAll(".scroll-animate");

  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add("active");
      } else {
        entry.target.classList.remove("active");
      }
    });
  }, { threshold: 0.2 });

  animatedItems.forEach(el => observer.observe(el));

  /* =========================
     CAROUSEL
  ========================= */
  const pCarousel = document.getElementById("programCarousel");

  document.getElementById("scrollProgramLeft").onclick = () =>
    pCarousel.scrollBy({ left: -320, behavior: "smooth" });

  document.getElementById("scrollProgramRight").onclick = () =>
    pCarousel.scrollBy({ left: 320, behavior: "smooth" });

  /* =========================
     FILTER PROGRAM
  ========================= */
  const pButtons = document.querySelectorAll(".filter-program");
  const programCards = document.querySelectorAll(".program-card");

  pButtons[0].classList.add("bg-green-600", "text-white", "border-green-600");

  pButtons.forEach(btn => {
    btn.addEventListener("click", () => {
      const filter = btn.dataset.filter;

      pButtons.forEach(b => {
        b.classList.remove("bg-green-600", "text-white", "border-green-600");
        b.classList.add("border-gray-300", "text-gray-700");
      });

      btn.classList.add("bg-green-600", "text-white", "border-green-600");

      programCards.forEach(card => {
        card.classList.toggle("hidden",
          !(filter === "Semua" || card.dataset.category === filter)
        );
      });
    });
  });

});
</script>
