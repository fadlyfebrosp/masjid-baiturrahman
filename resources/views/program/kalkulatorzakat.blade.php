@extends('layouts.app')

@section('title', 'Kalkulator Zakat')

@section('content')
<div
    class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-emerald-100
           flex items-center justify-center px-4 py-12"
    x-data="zakatCalculator()"
>

    <div class="w-full max-w-2xl">

        <!-- CARD -->
        <div class="bg-white/80 backdrop-blur rounded-3xl shadow-2xl border border-white p-8">

            <!-- HEADER -->
            <div class="text-center mb-8">
                <div class="mx-auto w-16 h-16 bg-emerald-100 rounded-full
                            flex items-center justify-center mb-4">
                    <i class="bi bi-calculator text-emerald-600 text-2xl"></i>
                </div>

                <h1 class="text-3xl font-extrabold text-emerald-700">
                    Kalkulator Zakat
                </h1>

                <p class="text-gray-500 mt-2 text-sm">
                    Hitung zakat sesuai ketentuan syariat Islam
                </p>
            </div>

            <!-- FORM -->
            <div class="space-y-5">

                <!-- JENIS ZAKAT -->
                <div>
                    <label for="" class="block text-sm font-semibold text-gray-700 mb-2">
                        Jenis Zakat
                    </label>

                    <select
                        x-model="jenis"
                        class="w-full rounded-xl border border-gray-300
                               px-4 py-3 bg-white
                               focus:outline-none focus:ring-2 focus:ring-emerald-500
                               focus:border-emerald-500 transition"
                    >
                        <option value="fitrah">Zakat Fitrah</option>
                        <option value="mal">Zakat Penghasilan / Mal</option>
                        <option value="emas">Zakat Emas</option>
                        <option value="pertanian">Zakat Pertanian</option>
                        <option value="peternakan">Zakat Peternakan</option>
                    </select>
                </div>

                <!-- NILAI -->
                <div>
                    <label for="" class="block text-sm font-semibold text-gray-700 mb-2">
                        Jumlah / Nilai
                    </label>

                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                            <i class="bi bi-cash-stack"></i>
                        </span>

                        <input
                            type="text"
                            x-model="nilai_display"
                            @input="formatInput"
                            placeholder="Masukkan nilai"
                            class="w-full rounded-xl border border-gray-300
                                pl-11 pr-4 py-3
                                focus:outline-none focus:ring-2 focus:ring-emerald-500
                                focus:border-emerald-500 transition"
                        >
                    </div>

                    <p class="text-xs text-gray-500 mt-1">
                        Rp (mal), gram (emas), kg (pertanian), ekor (ternak)
                    </p>
                </div>

                <!-- IRIGASI -->
                <div x-show="jenis === 'pertanian'" x-transition>
                    <label for="" class="block text-sm font-semibold text-gray-700 mb-2">
                        Metode Pengairan
                    </label>

                    <select
                        x-model="irigasi"
                        class="w-full rounded-xl border border-gray-300
                               px-4 py-3
                               focus:outline-none focus:ring-2 focus:ring-emerald-500
                               focus:border-emerald-500 transition"
                    >
                        <option value="tanpa">Tanpa Irigasi (10%)</option>
                        <option value="dengan">Dengan Irigasi (5%)</option>
                    </select>
                </div>

                <!-- ERROR -->
                <div
                    x-show="error"
                    x-transition
                    class="p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-600"
                    x-text="error"
                ></div>

                <!-- BUTTON -->
                <button
                    @click="hitung"
                    class="w-full py-3 rounded-xl font-semibold text-white
                           bg-gradient-to-r from-emerald-600 to-emerald-500
                           hover:from-emerald-700 hover:to-emerald-600
                           shadow-lg hover:shadow-xl
                           transition"
                >
                    Hitung Zakat
                </button>

                <!-- HASIL -->
                <div
                    x-show="hasil !== null"
                    x-transition
                    class="mt-6 rounded-2xl border border-emerald-200
                           bg-emerald-50 p-6"
                >
                    <h3 class="font-bold text-emerald-700 mb-2 flex items-center gap-2">
                        <i class="bi bi-check-circle-fill"></i>
                        Hasil Perhitungan
                    </h3>

                    <template x-if="typeof hasil === 'number'">
                        <p class="text-2xl font-extrabold text-emerald-700">
                            Rp <span x-text="hasil.toLocaleString('id-ID')"></span>
                        </p>
                    </template>

                    <template x-if="typeof hasil === 'string'">
                        <p class="font-semibold text-gray-800" x-text="hasil"></p>
                    </template>

                    <p class="mt-2 text-sm text-gray-600" x-text="keterangan"></p>
                </div>

                <!-- FOOTNOTE -->
                <p class="text-xs text-gray-400 text-center mt-6">
                    * Kalkulator ini bersifat edukatif dan tidak menggantikan
                    konsultasi dengan amil atau ulama.
                </p>

            </div>
        </div>
    </div>
</div>

<!-- ALPINE -->
<script>
function zakatCalculator() {
    return {
        jenis: 'fitrah',
        nilai: 0,              // angka asli (untuk hitung)
        nilai_display: '',     // tampilan dengan titik
        irigasi: 'tanpa',
        hasil: null,
        keterangan: '',
        error: '',

        HARGA_BERAS: 10000,
        HARGA_EMAS: 1200000,
        NISAB_EMAS: 85,
        NISAB_GABAH: 653,

        formatInput() {
            // hapus semua selain angka
            let raw = this.nilai_display.replace(/\D/g, '');

            // simpan nilai asli (number)
            this.nilai = raw ? parseInt(raw) : 0;

            // format tampilan pakai titik
            this.nilai_display = raw
                ? new Intl.NumberFormat('id-ID').format(raw)
                : '';
        },

        hitung() {
            this.error = '';
            this.hasil = null;
            this.keterangan = '';

            if (!this.nilai && this.jenis !== 'fitrah') {
                this.error = 'Silakan masukkan nilai terlebih dahulu.';
                return;
            }

            switch (this.jenis) {
                case 'fitrah':
                    this.hasil = 2.5 * this.HARGA_BERAS;
                    this.keterangan = 'Zakat fitrah setara 2,5 kg beras.';
                    break;

                case 'mal':
                    const nisabMal = this.HARGA_EMAS * this.NISAB_EMAS;
                    if (this.nilai < nisabMal) {
                        this.error =
                            `Belum mencapai nisab (Rp ${nisabMal.toLocaleString('id-ID')})`;
                        return;
                    }
                    this.hasil = this.nilai * 0.025;
                    this.keterangan = 'Zakat penghasilan sebesar 2,5%.';
                    break;

                case 'emas':
                    if (this.nilai < this.NISAB_EMAS) {
                        this.error = 'Belum mencapai nisab emas (85 gram).';
                        return;
                    }
                    this.hasil = this.nilai * this.HARGA_EMAS * 0.025;
                    this.keterangan = 'Zakat emas sebesar 2,5%.';
                    break;

                case 'pertanian':
                    if (this.nilai < this.NISAB_GABAH) {
                        this.error = 'Belum mencapai nisab (653 kg gabah).';
                        return;
                    }
                    this.hasil =
                        this.nilai * (this.irigasi === 'tanpa' ? 0.10 : 0.05);
                    this.keterangan = 'Zakat pertanian sesuai metode pengairan.';
                    break;

                case 'peternakan':
                    if (this.nilai < 30) {
                        this.error = 'Belum mencapai nisab peternakan.';
                        return;
                    }
                    if (this.nilai < 40) this.hasil = '1 ekor sapi (Tabi’)';
                    else if (this.nilai < 120) this.hasil = '1 ekor kambing';
                    else this.hasil =
                        Math.floor(this.nilai / 40) + ' ekor kambing';

                    this.keterangan =
                        'Zakat peternakan sesuai ketentuan syariat.';
                    break;
            }
        }
    }
}
</script>
@endsection
