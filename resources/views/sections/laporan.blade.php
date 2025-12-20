<section id="laporan" class="py-20 bg-green-50 text-center">
    <div class="max-w-6xl mx-auto px-6">
        <h2 class="text-3xl font-bold text-green-800 mb-6">
            Transparansi Keuangan
        </h2>

        <p class="text-gray-600 mb-10">
            Laporan keuangan Masjid Baiturrahman periode {{ $periode }}
        </p>

        <div class="grid md:grid-cols-3 gap-6 text-left">

            <!-- PEMASUKAN -->
            <div class="p-6 bg-white rounded-2xl shadow-md">
                <h3 class="text-lg font-semibold text-green-700 mb-2">
                    Total Pemasukan
                </h3>
                <p class="text-2xl font-bold text-green-800 mb-1">
                    Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                </p>
                <p class="text-gray-500 text-sm">
                    Bulan {{ $periode }}
                </p>
            </div>

            <!-- PENGELUARAN -->
            <div class="p-6 bg-white rounded-2xl shadow-md">
                <h3 class="text-lg font-semibold text-red-600 mb-2">
                    Total Pengeluaran
                </h3>
                <p class="text-2xl font-bold text-red-600 mb-1">
                    Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                </p>
                <p class="text-gray-500 text-sm">
                    Operasional & Kegiatan
                </p>
            </div>

            <!-- SALDO -->
            <div class="p-6 bg-white rounded-2xl shadow-md">
                <h3 class="text-lg font-semibold text-green-700 mb-2">
                    Saldo Akhir
                </h3>
                <p class="text-2xl font-bold text-green-700 mb-1">
                    Rp {{ number_format($saldoAkhir, 0, ',', '.') }}
                </p>
                <p class="text-gray-500 text-sm">
                    Per {{ now()->translatedFormat('d F Y') }}
                </p>
            </div>

        </div>

        <div class="mt-10">
            <a href="{{ route('laporan.index') }}" class="boton-elegante">
                Lihat Laporan Lengkap
            </a>
        </div>
    </div>
</section>
