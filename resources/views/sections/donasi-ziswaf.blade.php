<section id="donasi" class="py-20 bg-white text-center">
        <div class="max-w-6xl mx-auto px-6">
            <h2 class="text-3xl font-bold text-green-800 mb-6">Donasi ZISWAF</h2>
            <p class="text-gray-600 mb-10">
                Salurkan kebaikan Anda melalui program Zakat, Infak, Sedekah, dan Wakaf
                untuk mendukung kegiatan Masjid Baiturrahman dan masyarakat sekitar.
            </p>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Zakat -->
                <div class="bg-gray-50 rounded-2xl shadow p-6 hover:shadow-lg transition">
                    <div class="text-green-600 text-5xl mb-4">
                        <i class="bi bi-cash-coin"></i>
                    </div>
                    <h3 class="font-semibold text-lg mb-2">Zakat</h3>
                    <p class="text-gray-600 mb-4">
                        Tunaikan zakat Anda untuk membantu fakir miskin dan mendukung
                        program pemberdayaan umat.
                    </p>
                    <div class="bg-gray-200 rounded-full h-2 mb-3">
                        <div class="bg-green-600 h-2 rounded-full" style="width: 65%"></div>
                    </div>
                    <p class="text-sm text-gray-500 mb-4">
                        Terkumpul: Rp 65.000.000 dari Rp 100.000.000
                    </p>
                    <a href="{{ route('program.index', 'zakat')}}" class="boton-elegante">Bayar Zakat</a>
                </div>

                <!-- Infak & Sedekah -->
                <div class="bg-gray-50 rounded-2xl shadow p-6 hover:shadow-lg transition">
                    <div class="text-green-600 text-5xl mb-4">
                        <i class="bi bi-heart-fill"></i>
                    </div>
                    <h3 class="font-semibold text-lg mb-2">Infak & Sedekah</h3>
                    <p class="text-gray-600 mb-4">
                        Donasikan sebagian rezeki Anda untuk kegiatan sosial, pendidikan,
                        dan dakwah di lingkungan Masjid Baiturrahman.
                    </p>
                    <div class="bg-gray-200 rounded-full h-2 mb-3">
                        <div class="bg-green-600 h-2 rounded-full" style="width: 75%"></div>
                    </div>
                    <p class="text-sm text-gray-500 mb-4">
                        Terkumpul: Rp 37.500.000 dari Rp 50.000.000
                    </p>
                    <a href="{{ route('program.index', 'infak-sedekah') }}" class="boton-elegante">Donasi Sekarang</a>
                </div>

                <!-- Wakaf -->
                <div class="bg-gray-50 rounded-2xl shadow p-6 hover:shadow-lg transition">
                    <div class="text-green-600 text-5xl mb-4">
                        <i class="bi bi-building"></i>
                    </div>
                    <h3 class="font-semibold text-lg mb-2">Wakaf</h3>
                    <p class="text-gray-600 mb-4">
                        Jadikan amal jariyah Anda abadi melalui wakaf tanah, bangunan,
                        dan fasilitas pendukung masjid.
                    </p>
                    <div class="bg-gray-200 rounded-full h-2 mb-3">
                        <div class="bg-green-600 h-2 rounded-full" style="width: 50%"></div>
                    </div>
                    <p class="text-sm text-gray-500 mb-4">
                        Terkumpul: Rp 125.000.000 dari Rp 250.000.000
                    </p>
                    <a href="{{ route('program.index', 'wakaf') }}" class="boton-elegante">Wakaf Sekarang</a>
                </div>
            </div>
        </div>
    </section>
