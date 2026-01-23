<footer class="relative bg-green-50 pt-16 text-green-900">

  <div class="max-w-7xl mx-auto px-6">

    <!-- GRID UTAMA -->
    <div class="grid gap-12 lg:grid-cols-4 items-start">

      <!-- IDENTITAS MASJID -->
      <div class="order-1">
        <img src="{{ $logo }}" class="w-24 h-16 object-contain mb-4" alt="Logo Masjid">

        <p class="text-sm leading-relaxed text-green-800">
          Masjid Baiturrahman merupakan pusat ibadah, dakwah, pendidikan,
          dan kegiatan sosial yang dikelola secara amanah dan profesional
          untuk kemaslahatan umat.
        </p>

        <p class="mt-4 text-sm italic text-green-600">
          “Masjid sebagai pusat peradaban umat.”
        </p>
      </div>

      <!-- MEDIA SOSIAL -->
      <div class="order-2">
        <h2 class="text-lg font-semibold mb-4 text-green-900">
          Media Sosial
        </h2>
        <div class="h-0.5 w-16 bg-green-600 mb-5"></div>

        <ul class="space-y-3 text-sm text-green-800">
          <li class="flex items-center gap-3 hover:text-green-600 transition">
            <i class="bi bi-instagram text-green-600"></i> Instagram
          </li>
          <li class="flex items-center gap-3 hover:text-green-600 transition">
            <i class="bi bi-youtube text-green-600"></i> YouTube
          </li>
          <li class="flex items-center gap-3 hover:text-green-600 transition">
            <i class="bi bi-facebook text-green-600"></i> Facebook
          </li>
          <li class="flex items-center gap-3 hover:text-green-600 transition">
            <i class="bi bi-tiktok text-green-600"></i> TikTok
          </li>
        </ul>
      </div>

      <!-- PROGRAM DONASI -->
      <div class="order-3">
        <h2 class="text-lg font-semibold mb-4 text-green-900">
          Program Donasi
        </h2>
        <div class="h-0.5 w-16 bg-green-600 mb-5"></div>

        <ul class="space-y-2 text-sm text-green-800">
          @foreach (['zakat','infaq','sedekah','wakaf','hibah'] as $kategori)
            <li>
              <a href="{{ route('program.index', $kategori) }}"
                 class="hover:text-green-600 transition">
                • {{ ucfirst($kategori) }}
              </a>
            </li>
          @endforeach
        </ul>
      </div>

      <!-- CARD TRANSPARANSI (DESKTOP SEJAJAR, MOBILE DI BAWAH) -->
      <div class="order-4 lg:order-4">
        <div class="border border-green-300 rounded-xl p-5 text-xs leading-relaxed
                    text-green-700 bg-green-100 shadow-sm">

          <div class="flex items-center gap-2 mb-3 text-green-800 font-medium">
            <i class="bi bi-shield-check text-green-600 text-base"></i>
            Transparansi Donasi
          </div>

          Dana donasi yang dikelola Masjid Baiturrahman tidak bersumber dan
          tidak digunakan untuk kegiatan pencucian uang, terorisme, maupun
          tindak kejahatan lainnya.
        </div>
      </div>

    </div>
  </div>

  <!-- DIVIDER -->
  <div class="mt-14 border-t border-green-200"></div>

  <!-- COPYRIGHT -->
  <div class="py-5 text-center text-sm text-green-700">
    © {{ date('Y') }} Masjid Baiturrahman · v1.1
  </div>
</footer>
