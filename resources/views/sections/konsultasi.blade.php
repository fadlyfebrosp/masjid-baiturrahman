<style>
.scroll-animate {
    opacity: 0;
    transition: all 0.8s ease-out;
}

/* Arah animasi */
.from-bottom {
    transform: translateY(60px);
}

.from-left {
    transform: translateX(-60px);
}

.from-right {
    transform: translateX(60px);
}

/* Saat aktif */
.scroll-animate.active {
    opacity: 1;
    transform: translate(0, 0);
}
</style>
<section class="py-16 bg-white text-center">
    <div class="max-w-6xl mx-auto px-3">
        <div
            class="bg-green-700 text-white rounded-3xl p-10 flex flex-col md:flex-row items-center justify-between gap-10
                   scroll-animate from-bottom">

            <!-- Teks -->
            <div class="text-left md:w-1/2 scroll-animate from-left">
                <h2 class="text-3xl md:text-4xl font-bold mb-4 leading-snug">
                    Konsultasi Zakat Sekarang Mudah,<br class="hidden md:block">
                    Cukup Dari Rumah Saja!
                </h2>

                <p class="text-lg text-green-100 mb-8">
                    Kami siap melayani konsultasi Zakat untuk perorangan maupun perusahaan.
                </p>

                <div class="flex flex-wrap gap-4">
                    <a href="https://wa.me/6281234567890" target="_blank"
                       class="inline-flex items-center gap-2 bg-white text-green-700 px-6 py-3 rounded-full font-medium hover:bg-green-100 transition">
                        Konsultasi Sekarang
                    </a>

                    <a href="{{ route('kalkulator.zakat') }}"
                       class="inline-flex items-center gap-2 border border-white px-6 py-3 rounded-full font-medium hover:bg-white hover:text-green-700 transition">
                        Kalkulator Zakat
                    </a>
                </div>
            </div>

            <!-- Gambar -->
            <div class="md:w-1/2 flex justify-center scroll-animate from-right">
                <img src="https://cdn3d.iconscout.com/3d/premium/thumb/working-man-3d-illustration-download-in-png-blend-fbx-gltf-file-formats--office-workspace-people-pack-illustrations-5102298.png"
                     alt="Konsultasi Zakat" class="w-80 md:w-96">
            </div>
        </div>
    </div>
</section>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const elements = document.querySelectorAll(".scroll-animate");

    const observer = new IntersectionObserver(
        entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("active");
                } else {
                    entry.target.classList.remove("active");
                }
            });
        },
        {
            threshold: 0.2
        }
    );

    elements.forEach(el => observer.observe(el));
});
</script>
