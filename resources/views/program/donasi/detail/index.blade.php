<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- =========================
         PHP PREPARE OG DATA
    ========================= --}}
    @php
        use Illuminate\Support\Str;

        $ogTitle = $item->judul;

        $ogDescription = $item->deskripsi
            ? Str::limit(
                trim(preg_replace('/\s+/', ' ', strip_tags($item->deskripsi))),
                160
            )
            : 'Mari berdonasi untuk ' . $item->judul . '. Sedikit dari kita, berarti besar bagi mereka.';

        $ogImage = $item->foto
            ? asset('storage/' . $item->foto)
            : asset('assets/img/Image-not-found.png');

        $ogUrl = url()->current();
    @endphp

    <title>{{ $ogTitle }}</title>

    {{-- =========================
         OPEN GRAPH META
    ========================= --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $ogTitle }}">
    <meta property="og:description" content="{{ $ogDescription }}">
    <meta property="og:url" content="{{ $ogUrl }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    {{-- =========================
         TWITTER CARD
    ========================= --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $ogTitle }}">
    <meta name="twitter:description" content="{{ $ogDescription }}">
    <meta name="twitter:image" content="{{ $ogImage }}">

    {{-- =========================
         ASSETS
    ========================= --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        .mobile-frame {
            max-width: 480px;
            margin: 0 auto;
            background: white;
            min-height: 100vh;
        }
        .tinymce-content p { margin: 0 0 .75rem; }
        .tinymce-content ul, .tinymce-content ol {
            margin: .5rem 0 .75rem 1.2rem;
            padding-left: 1.2rem;
        }
    </style>
</head>

<body class="bg-gray-100">
@php
    $terkumpul = $item->terkumpul ?? 0;
    $target    = $item->target_dana ?? 1;
    $persen    = min(100, ($terkumpul / $target) * 100);
@endphp

<div class="mobile-frame"
    x-data="{
        openModal:false,
        openShare:false,
        nominal:0,
        minNominal: {{ $item->min_donasi }},
        custom: {{ json_encode($item->custom_nominal ?? []) }},
        isExpired: {{ $isExpired ? 'true' : 'false' }},
        /* =====================
           DRAG STATE (INI BARU)
        ====================== */
        dragY: 0,
        startY: 0,
        dragging: false,

        startDrag(e) {
            this.dragging = true
            this.startY = e.touches ? e.touches[0].clientY : e.clientY
        },

        onDrag(e) {
            if (!this.dragging) return
            const currentY = e.touches ? e.touches[0].clientY : e.clientY
            this.dragY = Math.max(0, currentY - this.startY)
        },

        endDrag(close) {
            this.dragging = false
            if (this.dragY > 120) close()
            this.dragY = 0
        },
        pilih(n){
            this.nominal = n;
        },

        donasiSekarang(){
            if (this.nominal < this.minNominal) {
                alert('Nominal minimal donasi adalah Rp ' + this.minNominal.toLocaleString('id-ID'));
                return;
            }
            if (this.isExpired) {
                alert('Mohon maaf, waktu donasi telah berakhir.');
                return;
            }
            document.getElementById('formDonasi').submit();
        }
    }"
>

    <!-- ============================
         HEADER IMAGE
    ============================= -->
    <div class="relative w-full h-64 overflow-hidden mb-5">
        <a href="/"
            class="absolute top-4 left-4 z-10 flex items-center gap-2
                    bg-white/20 backdrop-blur-md
                    px-3 py-1.5 rounded-full shadow
                    transition-all duration-150 ease-out
                    active:scale-95 active:bg-white/70
                    hover:bg-white/60">
                <i class="bi bi-house-door text-green-700 text-lg"></i>
                <span class="font-medium text-gray-800 text-sm">Home</span>
         </a>

        <img
            src="{{ $item->foto ? asset('storage/' . $item->foto) : asset('assets/img/Image-not-found.png') }}"
            alt="{{ $item->judul }}"
            class="w-full h-full bg-gray-100"
        >
    </div>


    <!-- ============================
         MAIN CONTENT
    ============================= -->
    <div class="p-6 -mt-6 bg-white rounded-t-3xl shadow-md space-y-4">

        <!-- JUDUL -->
        <h1 class="text-xl font-bold leading-snug">
            {{ $item->judul }}
        </h1>
        @if($item->sub_kategori)
            <p class="text-sm text-green-700 font-medium">
                {{ $item->sub_kategori_label }}
            </p>
        @endif

        <!-- TERKUMPUL / TARGET -->
        <div class="text-sm text-gray-700">
            <b>Rp {{ number_format($terkumpul,0,',','.') }}</b>
            terkumpul dari
            <b>Rp {{ number_format($item->target_dana, 0, ',', '.') }}</b>
        </div>

        <!-- PROGRESS BAR -->
        <div class="w-full bg-gray-200 rounded-full h-2">
            <div
                class="bg-green-600 h-2 rounded-full"
                style="width: {{ $persen }}%">
            </div>
        </div>

        <!-- PERSEN -->
        <p class="text-xs text-gray-500">
            {{ number_format($persen, 0) }}% tercapai
        </p>
        <div class="flex justify-between text-gray-600 text-xs w-full">

            <span>{{ $jumlahDonasi }} Donasi</span>

            <div class="flex flex-col items-end leading-tight">
                <span class="font-medium text-gray-700">Sisa Waktu:</span>
                <span class="text-gray-500">{{ $sisaHari }}</span>
            </div>
        </div>

        <!-- BUTTON -->
        <button
            class="w-full py-3 rounded-lg font-semibold transition"
            :class="isExpired
                ? 'bg-gray-400 text-gray-700 cursor-not-allowed'
                : 'bg-green-600 text-white hover:bg-green-700'"
            :disabled="isExpired"
            @click="!isExpired && (openModal = true)"
        >
            <template x-if="!isExpired">
                <span>Infaq Sekarang!</span>
            </template>
            <template x-if="isExpired">
                <span>Donasi Telah Berakhir</span>
            </template>
        </button>

    </div>

    <!-- ============================
         PENGGALANG DANA
    ============================= -->
    <div class="p-6 bg-white rounded-xl my-4 shadow-sm">
        <h2 class="font-semibold mb-3">Penggalang Dana</h2>

        <div class="flex items-center gap-3">
            <img alt="gambar" src="{{ asset('assets/img/icon.png') }}" class="w-12 h-12 rounded-lg">
            <div>
                <p class="font-semibold">Masjid Baiturrahman</p>
                <p class="text-sm text-gray-500">Verified Organization</p>
            </div>
        </div>
    </div>

    <!-- ============================
     TAB KETERANGAN & DONATUR
    ============================= -->
    <div x-data="{ tab: 'keterangan' }" class="bg-white shadow-sm rounded-xl mt-4">

        <!-- TAB HEADER -->
        <div class="border-b flex">
            <button
                @click="tab = 'keterangan'"
                :class="tab === 'keterangan' ? 'border-b-2 border-green-600 text-green-600 font-semibold' : 'text-gray-500'"
                class="flex-1 py-3 text-center">
                Keterangan
            </button>

            <button
                @click="tab = 'donatur'"
                :class="tab === 'donatur' ? 'border-b-2 border-green-600 text-green-600 font-semibold' : 'text-gray-500'"
                class="flex-1 py-3 text-center">
                Donatur ({{ $donaturs->count() }})
            </button>
        </div>

        <!-- TAB CONTENT -->
        <div class="p-6">

            <!-- TAB KETERANGAN -->
            <div x-show="tab === 'keterangan'" class="tinymce-content">
                {!! $item->deskripsi !!}
            </div>
            <!-- DONATUR -->
            <div x-show="tab==='donatur'">
                @forelse($donaturs as $d)
                    <div class="border rounded-xl p-4 mb-3">
                        <div class="flex justify-between">
                            <p class="font-semibold">
                                {{ $d->anonim ? 'Orang Baik' : $d->nama_donatur }}
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ $d->created_at->diffForHumans() }}
                            </p>
                        </div>

                        <p class="text-sm mt-1 text-gray-600">
                            Berdonasi sebesar
                            <span class="font-semibold text-gray-900">
                                Rp {{ number_format($d->nominal,0,',','.') }}
                            </span>
                        </p>
                    </div>
                @empty
                    <p class="text-center text-gray-500 py-6">Belum ada donatur</p>
                @endforelse
            </div>

        </div>
    </div>
    <!-- ============================
        TIPS CARA DONASI
    ============================= -->
    <div class="p-6 bg-white shadow-sm rounded-xl my-4">
        <div class="flex items-start gap-3">
            <div class="text-green-600 text-2xl">
                <i class="bi bi-lightbulb"></i>
            </div>

            <div class="w-full">
                <h2 class="font-semibold text-gray-800 mb-3">
                    Tips Cara Donasi
                </h2>

                <ol class="list-decimal pl-5 space-y-2 text-sm text-gray-700">
                    <li>
                        Masukkan jumlah nominal atau pilih nominal yang tersedia.
                    </li>
                    <li>
                        Masukkan <b>Nama Lengkap</b>. Jika tidak ingin menampilkan nama,
                        silakan pilih <b>Sembunyikan nama saya</b>, lalu isi
                        <b>Nomor Telepon</b>, <b>Email</b>, dan <b>Pesan atau Doa</b>.
                    </li>
                    <li>
                        Pilih metode pembayaran yang tersedia.
                    </li>
                    <li>
                        Lakukan pembayaran sesuai instruksi.
                    </li>
                    <li>
                        Invoice akan ditampilkan dan dikirim ke email Anda.
                    </li>
                </ol>
            </div>
        </div>
    </div>
    <!-- ============================
        DOA AREA
    ============================= -->
    @php
        $doas = $donaturs->filter(fn($d) => !empty($d->deskripsi));
    @endphp

    <div class="p-6 bg-white shadow-sm rounded-xl my-4">

        <!-- JUDUL -->
        <h2 class="font-semibold mb-4 text-center">
            Doa-doa Orang Baik
            @if(!$doas->isEmpty())
                ({{ $doas->count() }})
            @endif
        </h2>

        @if($doas->isEmpty())
            {{-- ================= EMPTY STATE ================= --}}
            <div class="text-center">
                <img
                    alt="gambar"
                    src="{{ asset('assets/img/doa.jpeg') }}"
                    class="w-24 h-24 mx-auto mb-2">

                <p class="text-gray-600 text-sm">
                    Menanti doa-doa orang baik
                </p>
            </div>
        @else
            {{-- ================= LIST DOA ================= --}}
            <div class="space-y-4 text-left">
                @foreach($doas as $d)
                    <div class="bg-gray-50 p-4 rounded-lg border">

                        <div class="flex justify-between mb-1">
                            <p class="font-semibold text-gray-800">
                                {{ $d->anonim ? 'Orang Baik' : $d->nama_donatur }}
                            </p>

                            <p class="text-sm text-gray-500 whitespace-nowrap">
                                {{ $d->created_at->diffForHumans() }}
                            </p>
                        </div>

                        <p class="text-gray-700 text-sm leading-relaxed">
                            {{ $d->deskripsi }}
                        </p>

                    </div>
                @endforeach
            </div>
        @endif
    </div>
    <!-- ============================
        DISCLAIMER
    ============================= -->
    <div class="px-6 pb-6">
        <div class="p-4 bg-gray-50 border-l-4 border-green-600 rounded-xl">
            <p class="text-sm text-gray-700 leading-relaxed">
                <b>Disclaimer:</b><br>
                Cukup dengan minimal
                <span class="font-semibold text-green-700">
                    Rp {{ number_format($item->min_donasi,0,',','.') }}
                </span>
                Anda sudah berkontribusi dalam
                <span class="font-semibold">
                    {{ $item->judul }}
                </span>.
            </p>
        </div>
    </div>

    <!-- ============================
         FOOTER BUTTON
    ============================= -->
    <div class="border-t bg-white p-4">
        <div class="flex gap-3">

            <!-- SHARE -->
            <button
                class="flex items-center gap-2 px-4 py-3
                       border border-green-200 rounded-xl
                       text-green-700 font-medium
                       hover:bg-green-50 hover:text-green-800
                       transition"
                @click="openShare = true"
            >
                <!-- ICON SHARE -->
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-5 h-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M15 8a3 3 0 10-2.83-4H12a3 3 0 103 3
                             M9 16a3 3 0 10-2.83-4H6a3 3 0 103 3
                             M15 20a3 3 0 10-2.83-4H12a3 3 0 103 3"/>
                </svg>

                <span>Bagikan</span>
            </button>

            <!-- DONASI -->
            <button
                class="flex-1 py-3 rounded-xl font-semibold text-lg
                    shadow-md transition-all duration-200"
                :class="isExpired
                    ? 'bg-gray-400 text-gray-700 cursor-not-allowed'
                    : 'bg-green-600 text-white hover:bg-green-700'"
                    text-white hover:from-green-600 hover:to-green-700
                    active:scale-[0.98]'"
                :disabled="isExpired"
                @click="!isExpired && (openModal = true)"
            >
                <template x-if="!isExpired">
                    <span>Infaq Sekarang</span>
                </template>
                <template x-if="isExpired">
                    <span>Donasi Telah Berakhir</span>
                </template>
            </button>

        </div>
    </div>

    <!-- ======================================================
         FORM POST TERSEMBUNYI (WAJIB ADA — TANPA NOMINAL DI URL)
    ======================================================= -->
    <form id="formDonasi" method="POST"
        action="{{ route('donasi.form.post', [$item->kategori, $item->slug]) }}">
        @csrf
        <input type="hidden" name="nominal" x-model="nominal">
    </form>

    <!-- ============================
         MODAL DONASI
    ============================= -->
    <div x-show="openModal"
         x-transition.opacity
         class="fixed inset-0 bg-black/60 flex items-end justify-center z-50"
         @click.self="openModal=false">

        <div
        x-transition.origin.bottom
        :style="`transform: translateY(${dragY}px)`"
        class="bg-white w-full max-w-[480px] mx-auto p-6 rounded-t-2xl
               transition-transform duration-150
               cursor-grab active:cursor-grabbing"

        @mousedown="startDrag($event)"
        @mousemove="onDrag($event)"
        @mouseup="endDrag(() => openModal = false)"
        @mouseleave="dragging && endDrag(() => openModal = false)"

        @touchstart="startDrag($event)"
        @touchmove="onDrag($event)"
        @touchend="endDrag(() => openModal = false)">
            <div class="w-16 h-1 bg-gray-300 rounded-full mx-auto mb-4"></div>

            <h2 class="text-lg font-bold mb-4">Donasi Sekarang</h2>

            <div class="p-3 border rounded-lg flex items-center justify-between">
                <span>Rp</span>
                <input type="text" x-model="nominal"
                    class="w-full text-right outline-none font-bold"
                    placeholder="0">
            </div>

            <p class="mt-4 text-sm">Pilih Nominal Lainnya</p>

            <template x-for="n in custom">
                <div class="flex justify-between p-3 border rounded-lg mt-2 bg-green-50">
                    <span class="font-semibold" x-text="'Rp ' + n.toLocaleString('id-ID')"></span>
                    <button class="text-green-600 font-semibold" @click="pilih(n)">Pilih ></button>
                </div>
            </template>

            <button class="w-full mt-6 py-3 bg-red-600 text-white rounded-full font-semibold"
                    @click="donasiSekarang()">
                Donasi Sekarang <i class="bi bi-arrow-right"></i>
            </button>
        </div>
    </div>

    <!-- ============================
        MODAL SHARE
    ============================= -->
    <div x-show="openShare"
        x-transition.opacity
        class="fixed inset-0 bg-black/60 flex items-end justify-center z-50"
        @click.self="openShare = false">

        <div
        x-transition.origin.bottom
        :style="`transform: translateY(${dragY}px)`"
        class="bg-white w-full max-w-[480px] mx-auto p-6 rounded-t-2xl
               transition-transform duration-150
               cursor-grab active:cursor-grabbing"

        @mousedown="startDrag($event)"
        @mousemove="onDrag($event)"
        @mouseup="endDrag(() => openShare = false)"
        @mouseleave="dragging && endDrag(() => openShare = false)"

        @touchstart="startDrag($event)"
        @touchmove="onDrag($event)"
        @touchend="endDrag(() => openShare = false)"
    >

            <div class="w-16 h-1 bg-gray-300 rounded-full mx-auto mb-4"></div>

            <h2 class="text-lg font-bold mb-4 text-center">
                Bagikan Donasi
            </h2>

            <div class="grid grid-cols-3 gap-4 text-center text-sm">

                <!-- WHATSAPP -->
                <a
                    :href="`https://wa.me/?text=${encodeURIComponent('{{ $item->judul }} - ' + window.location.href)}`"
                    target="_blank"
                    class="flex flex-col items-center gap-2">
                    <i class="bi bi-whatsapp text-3xl text-green-600"></i>
                    WhatsApp
                </a>

                <!-- FACEBOOK -->
                <a
                    :href="`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(window.location.href)}`"
                    target="_blank"
                    class="flex flex-col items-center gap-2">
                    <i class="bi bi-facebook text-3xl text-blue-600"></i>
                    Facebook
                </a>

                <!-- LINKEDIN -->
                <a
                    :href="`https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(window.location.href)}`"
                    target="_blank"
                    class="flex flex-col items-center gap-2">
                    <i class="bi bi-linkedin text-3xl text-blue-700"></i>
                    LinkedIn
                </a>

                <!-- LINE -->
                <a
                    :href="`https://social-plugins.line.me/lineit/share?url=${encodeURIComponent(window.location.href)}`"
                    target="_blank"
                    class="flex flex-col items-center gap-2">
                    <i class="bi bi-chat-dots text-3xl text-green-500"></i>
                    LINE
                </a>

                <!-- EMAIL -->
                <a
                    :href="`mailto:?subject={{ $item->judul }}&body=${encodeURIComponent(window.location.href)}`"
                    class="flex flex-col items-center gap-2">
                    <i class="bi bi-envelope text-3xl text-gray-700"></i>
                    Email
                </a>

                <!-- COPY LINK -->
                <button
                    @click="
                        navigator.clipboard.writeText(window.location.href)
                        .then(() => alert('Link berhasil disalin ke clipboard!'))
                        .catch(() => alert('Gagal menyalin link'))
                    "
                    class="flex flex-col items-center gap-2">
                    <i class="bi bi-link-45deg text-3xl text-gray-700"></i>
                    Salin Link
                </button>

            </div>

            <button
                class="w-full mt-6 py-3 border rounded-lg font-semibold"
                @click="openShare = false">
                Tutup
            </button>
        </div>
    </div>

</div>

</body>
</html>
