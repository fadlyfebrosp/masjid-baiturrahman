<style>
    .tinymce-content p {
        margin: 0 0 .75rem;
    }

    .tinymce-content ul,
    .tinymce-content ol {
        margin: .5rem 0 .75rem 1.2rem;
        padding-left: 1.2rem;
        list-style-position: outside;
        color: #000;
    }

    .tinymce-content li {
        margin-bottom: 4px;
    }

    /* Bullet + numbering selalu hitam */
    .tinymce-content li::marker {
        color: #000 !important;
    }

    .tinymce-content img {
        max-width: 100%;
        display: inline-block;
        margin: 10px 0;
    }
</style>

<div class="border rounded-xl overflow-hidden bg-white shadow-md"
    :class="previewMode === 'mobile' ? 'max-w-sm mx-auto' : ''">

    <!-- IMAGE -->
    <img
        :src="preview || defaultImage"
        class="w-full h-64 object-cover"
        alt="Preview Program"
    >

    <div class="p-6 space-y-4">

        <!-- JUDUL PROGRAM -->
        <h1 class="text-2xl font-bold text-gray-900"
            x-text="form.nama_produk || 'Judul Program'">
            Judul Program
        </h1>

        <!-- TOTAL & TARGET DONASI -->
        <div class="text-gray-700 text-base">
            <span class="font-semibold text-black">Rp 0</span>
            <span> terkumpul dari </span>
            <span class="font-bold" x-text="formatRupiah(form.target_dana || 0)"></span>
        </div>

        <!-- PROGRESS BAR -->
        <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="bg-green-600 h-2 rounded-full"
                 :style="`width: ${(0 / (form.target_dana || 1)) * 100}%`">
            </div>
        </div>

        <!-- SISA WAKTU -->
        <div class="flex justify-between text-gray-600 text-sm w-full">

            <span>0 Donasi</span>

            <div class="flex flex-col items-end leading-tight">
                <span class="font-medium text-gray-700">Sisa Waktu:</span>

                <!-- OPEN GOALS -->
                <span x-show="form.open_goals" class="text-gray-500">
                    Tanpa Batas Waktu
                </span>

                <!-- NON GOALS -->
                <span x-show="!form.open_goals"
                      x-text="form.target_date
                        ? (remainingDays() + ' hari lagi')
                        : 'Belum diatur'">
                </span>
            </div>

        </div>

        <!-- BUTTON DONASI -->
        <button class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700">
            Infaq Sekarang!
        </button>
    </div>


    <!-- PENGGALANG DANA -->
    <div class="p-6 border-t">
        <h2 class="font-semibold text-gray-800 mb-3">Penggalang Dana</h2>

        <div class="flex items-center gap-3">
            <img src="{{ asset('assets/img/icon.png') }}"
                 class="w-12 h-12 rounded-lg"
                 alt="Logo Organisasi">

            <div>
                <p class="font-semibold text-gray-900">Masjid Baiturrahman</p>
                <p class="text-gray-500 text-sm">Verified Organization</p>
            </div>
        </div>
    </div>


    <!-- TAB NAVIGASI -->
    <div class="border-t">
        <div class="flex text-sm">
            <button class="flex-1 py-3 font-semibold text-blue-600 border-b-2 border-blue-600">
                Keterangan
            </button>
            <button class="flex-1 py-3 text-gray-500">Kabar Terbaru</button>
            <button class="flex-1 py-3 text-gray-500">Donatur</button>
        </div>

        <!-- ISI KETERANGAN -->
        <div class="p-6 text-gray-700 leading-relaxed text-sm space-y-2">

            <!-- RINGKASAN DESKRIPSI -->
            <div x-show="!showFullDesc" class="tinymce-content">
                <div x-html="shortDescription"></div>
            </div>

            <!-- DESKRIPSI FULL -->
            <div x-show="showFullDesc" class="tinymce-content">
                <div x-html="form.produk_deskripsi || 'Belum ada deskripsi'"></div>
            </div>

            <!-- TOGGLE DESKRIPSI -->
            <button
                x-show="hasLongDescription"
                @click="showFullDesc = !showFullDesc"
                class="mt-4 w-full text-center bg-blue-50 text-blue-600 font-medium py-2 rounded-lg">

                <span x-text="showFullDesc ? 'Tutup ▲' : 'Baca selengkapnya ▾'"></span>
            </button>

        </div>
    </div>


    <!-- DOA-DOA -->
    <div class="p-6 border-t space-y-3">
        <h2 class="font-semibold text-gray-800">Doa-doa orang baik</h2>

        <div class="flex flex-col items-center text-center gap-2">
            <img src="{{ asset('assets/img/doa.jpeg') }}" class="w-24 h-24" alt="Ilustrasi Doa">
            <p class="text-gray-600">Menanti doa-doa orang baik</p>
        </div>
    </div>


    <!-- SHARE & DONASI -->
    <div class="border-t bg-gray-50 p-4 flex gap-3">
        <button class="flex-1 py-3 border border-gray-300 rounded-lg font-medium">
            Share
        </button>
        <button class="flex-1 py-3 bg-green-600 text-white rounded-lg font-semibold">
            Infaq Sekarang!
        </button>
    </div>

</div>
