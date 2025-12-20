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

/* Delay bertahap */
.delay-0 { transition-delay: 0s; }
.delay-1 { transition-delay: .15s; }
.delay-2 { transition-delay: .3s; }
</style>
@php
    $zakatTerkumpul = $kategoriSummary['Zakat']->total_terkumpul ?? 0;
    $zakatTarget    = $kategoriSummary['Zakat']->total_target ?? 0;
    $zakatPersen    = $zakatTarget > 0
        ? min(100, ($zakatTerkumpul / $zakatTarget) * 100)
        : 0;

    $infakSedekahTerkumpul =
        ($kategoriSummary['Infak']->total_terkumpul ?? 0) +
        ($kategoriSummary['Sedekah']->total_terkumpul ?? 0);

    $infakSedekahTarget =
        ($kategoriSummary['Infak']->total_target ?? 0) +
        ($kategoriSummary['Sedekah']->total_target ?? 0);

    $infakSedekahPersen = $infakSedekahTarget > 0
        ? min(100, ($infakSedekahTerkumpul / $infakSedekahTarget) * 100)
        : 0;

    $wakafTerkumpul = $kategoriSummary['Wakaf']->total_terkumpul ?? 0;
    $wakafTarget    = $kategoriSummary['Wakaf']->total_target ?? 0;
    $wakafPersen    = $wakafTarget > 0
        ? min(100, ($wakafTerkumpul / $wakafTarget) * 100)
        : 0;
@endphp
<section id="donasi" class="py-20 bg-white text-center">
    <div class="max-w-6xl mx-auto px-6">

        <!-- JUDUL -->
        <h2 class="text-3xl font-bold text-green-800 mb-6 scroll-animate from-top">
            Donasi ZISWAF
        </h2>

        <!-- DESKRIPSI -->
        <p class="text-gray-600 mb-10 scroll-animate from-bottom">
            Salurkan kebaikan Anda melalui program Zakat, Infak, Sedekah, dan Wakaf
            untuk mendukung kegiatan Masjid Baiturrahman dan masyarakat sekitar.
        </p>

        <!-- CARD DONASI -->
        <div class="grid md:grid-cols-3 gap-8">

            <!-- ZAKAT -->
            <div class="bg-gray-50 rounded-2xl shadow p-6 hover:shadow-lg transition
                        scroll-animate from-bottom delay-0">
                <div class="text-green-600 text-5xl mb-4">
                    <i class="bi bi-cash-coin"></i>
                </div>
                <h3 class="font-semibold text-lg mb-2">Zakat</h3>
                <p class="text-gray-600 mb-4">
                    Tunaikan zakat Anda untuk membantu fakir miskin dan mendukung
                    program pemberdayaan umat.
                </p>
                <div class="bg-gray-200 rounded-full h-2 mb-3">
                    <div class="bg-green-600 h-2 rounded-full"
                        style="width: {{ $zakatPersen }}%"></div>
                </div>

                <p class="text-sm text-gray-500 mb-4">
                    Terkumpul: Rp {{ number_format($zakatTerkumpul, 0, ',', '.') }}
                    dari Rp {{ number_format($zakatTarget, 0, ',', '.') }}
                </p>
                <a href="{{ route('program.index', 'zakat') }}"
                   class="boton-elegante">
                    Bayar Zakat
                </a>
            </div>

            <!-- INFAK & SEDEKAH -->
            <div class="bg-gray-50 rounded-2xl shadow p-6 hover:shadow-lg transition
                        scroll-animate from-bottom delay-1">
                <div class="text-green-600 text-5xl mb-4">
                    <i class="bi bi-heart-fill"></i>
                </div>
                <h3 class="font-semibold text-lg mb-2">Infak & Sedekah</h3>
                <p class="text-gray-600 mb-4">
                    Donasikan sebagian rezeki Anda untuk kegiatan sosial, pendidikan,
                    dan dakwah di lingkungan Masjid Baiturrahman.
                </p>
                <div class="bg-gray-200 rounded-full h-2 mb-3">
                    <div class="bg-green-600 h-2 rounded-full"
                        style="width: {{ $infakSedekahPersen }}%"></div>
                </div>
                <p class="text-sm text-gray-500 mb-4">
                    Terkumpul: Rp {{ number_format($infakSedekahTerkumpul, 0, ',', '.') }}
                    dari {{ number_format($infakSedekahTarget, 0, ',', '.') }}
                </p>
                <a href="{{ route('program.index', 'infak-sedekah') }}"
                   class="boton-elegante">
                    Donasi Sekarang
                </a>
            </div>

            <!-- WAKAF -->
            <div class="bg-gray-50 rounded-2xl shadow p-6 hover:shadow-lg transition
                        scroll-animate from-bottom delay-2">
                <div class="text-green-600 text-5xl mb-4">
                    <i class="bi bi-building"></i>
                </div>
                <h3 class="font-semibold text-lg mb-2">Wakaf</h3>
                <p class="text-gray-600 mb-4">
                    Jadikan amal jariyah Anda abadi melalui wakaf tanah, bangunan,
                    dan fasilitas pendukung masjid.
                </p>
                <div class="bg-gray-200 rounded-full h-2 mb-3">
                    <div class="bg-green-600 h-2 rounded-full"
                        style="width: {{ $wakafPersen }}%"></div>
                </div>
                <p class="text-sm text-gray-500 mb-4">
                    Terkumpul: Rp {{ number_format($wakafTerkumpul, 0, ',', '.') }}
                    dari {{ number_format($wakafTarget, 0, ',', '.') }}
                </p>
                <a href="{{ route('program.index', 'wakaf') }}"
                   class="boton-elegante">
                    Wakaf Sekarang
                </a>
            </div>

        </div>
    </div>
</section>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const elements = document.querySelectorAll(".scroll-animate");

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add("active");
            } else {
                entry.target.classList.remove("active");
            }
        });
    }, { threshold: 0.2 });

    elements.forEach(el => observer.observe(el));
});
</script>
