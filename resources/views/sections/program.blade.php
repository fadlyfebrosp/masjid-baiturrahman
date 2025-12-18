<section class="py-16 bg-white text-center">
    <div class="max-w-5xl mx-auto px-6">

        <h2 class="text-3xl font-bold text-gray-900 mb-4"
            data-aos="fade-up"
            data-aos-anchor-placement="center-bottom">
            Program Masjid Baiturrahman
        </h2>

        <p class="text-gray-600 mb-10"
           data-aos="fade-up"
           data-aos-delay="150"
           data-aos-anchor-placement="center-bottom">
            Salurkan Infaq, Zakat, dan Wakaf Anda dengan mudah bersama
            <span class="text-green-600 font-semibold">Masjid Baiturrahman</span>.
            Silahkan pilih program yang Anda inginkan:
        </p>

        <div class="grid md:grid-cols-3 gap-8 text-left">

            <!-- INFAQ -->
            <div class="p-6 rounded-2xl shadow-md hover:shadow-lg transition"
                 data-aos="fade-up"
                 data-aos-delay="200"
                 data-aos-anchor-placement="center-bottom">
                <img src="https://cdn-icons-png.flaticon.com/512/10133/10133125.png" alt="" class="w-16 mb-4">
                <h3 class="text-lg font-semibold mb-2">Infaq</h3>
                <p class="text-gray-600 mb-4">
                    Infaq Anda adalah harapan bagi mereka yang membutuhkan
                </p>
                <a href="{{ route('program.index', 'infaq') }}"
                   class="text-green-600 font-medium hover:underline">
                    Mulai Infaq →
                </a>
            </div>

            <!-- ZAKAT -->
            <div class="p-6 rounded-2xl shadow-md hover:shadow-lg transition"
                 data-aos="fade-up"
                 data-aos-delay="350"
                 data-aos-anchor-placement="center-bottom">
                <img src="https://cdn-icons-png.flaticon.com/512/8265/8265048.png" alt="" class="w-16 mb-4">
                <h3 class="text-lg font-semibold mb-2">Zakat</h3>
                <p class="text-gray-600 mb-4">
                    Sucikan jiwa Anda dengan menunaikan zakat
                </p>
                <a href="{{ route('program.index', 'zakat') }}"
                   class="text-green-600 font-medium hover:underline">
                    Mulai Zakat →
                </a>
            </div>

            <!-- WAKAF -->
            <div class="p-6 rounded-2xl shadow-md hover:shadow-lg transition"
                 data-aos="fade-up"
                 data-aos-delay="500"
                 data-aos-anchor-placement="center-bottom">
                <img src="https://cdn-icons-png.flaticon.com/512/9946/9946062.png" alt="" class="w-16 mb-4">
                <h3 class="text-lg font-semibold mb-2">Wakaf</h3>
                <p class="text-gray-600 mb-4">
                    Satu wakaf akan menjadi kebaikan abadi bagi umat
                </p>
                <a href="{{ route('program.index', 'wakaf') }}"
                   class="text-green-600 font-medium hover:underline">
                    Mulai Wakaf →
                </a>
            </div>

        </div>
    </div>
</section>
