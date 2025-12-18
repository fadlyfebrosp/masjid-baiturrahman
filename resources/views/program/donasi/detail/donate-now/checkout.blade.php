<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - {{ $program->judul }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        .mobile-frame {
            max-width: 480px;
            margin: 0 auto;
            background: white;
            min-height: 100vh;
        }
    </style>
</head>

<body class="bg-gray-100">

<div class="mobile-frame"
    x-data="{
        nominal: {{ $nominal }},
        foto: '{{ $program->foto ? asset('storage/'.$program->foto) : asset('assets/img/Image-not-found.png') }}',
        judul: '{{ $program->judul }}',

        sapaan: 'Bapak',

        nama: '{{ Auth::check() ? Auth::user()->name : '' }}',
        namaBackup: '',
        telepon: '{{ Auth::check() ? Auth::user()->phone : '' }}',
        email: '{{ Auth::check() ? Auth::user()->email : '' }}',

        anonim: false,
    }"
>

    <!-- HEADER -->
    <div class="flex items-center gap-3 p-4 border-b">
        <a href="{{ url()->previous() }}" class="text-gray-600 text-2xl">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="font-semibold text-gray-800" x-text="judul">
            {{ $program->judul }}
        </h1>
    </div>

    <!-- PROGRAM -->
    <div class="p-4 flex gap-3 border-b">
        <img :src="foto"
            alt="Gambar program donasi {{ $program->judul }}"
            class="w-24 h-24 rounded-lg object-cover">
        <div>
            <p class="text-sm text-gray-500">Anda akan berdonasi pada program:</p>
            <p class="font-semibold text-gray-900 text-sm" x-text="judul"></p>
            <p class="mt-1 text-green-700 font-bold text-sm"
               x-text="'Nominal: Rp ' + Number(nominal).toLocaleString('id-ID')"></p>
        </div>
    </div>

    <!-- FORM -->
    <form method="POST"
          action="{{ route('donasi.store', [$program->kategori, $program->slug]) }}"
          class="p-4 space-y-4">

        @csrf
        <input type="hidden" name="nominal" :value="nominal">

        <!-- Sapaan -->
        <div>
            <p class="font-medium mb-2">Sapaan</p>
            <div class="grid grid-cols-3 gap-2">
                <template x-for="s in ['Bapak','Ibu','Kak']" :key="s">
                    <button type="button"
                        @click="sapaan = s"
                        :class="sapaan === s ? 'bg-green-600 text-white' : 'border'"
                        class="py-2 rounded-lg font-semibold">
                        <span x-text="s"></span>
                    </button>
                </template>
            </div>
            <input type="hidden" name="sapaan" :value="sapaan">
        </div>

        <!-- Nama -->
        <div>
            <input type="text"
                name="nama_donatur_display"
                x-model="nama"
                :disabled="anonim"
                :class="anonim
                    ? 'w-full border rounded-lg p-3 bg-gray-100 text-gray-500 cursor-not-allowed'
                    : 'w-full border rounded-lg p-3 focus:ring-2 focus:ring-green-500'"
                placeholder="Nama Lengkap"
                required>
            <input type="hidden" name="nama_donatur" :value="namaBackup || nama">
        </div>

        <!-- Anonim -->
        <div class="border rounded-xl p-4 flex items-center justify-between bg-gray-50">
            <div class="flex gap-3">
                <i class="bi bi-eye-slash text-green-600 text-xl"></i>
                <div>
                    <p class="font-medium text-gray-800">Sembunyikan nama saya</p>
                    <p class="text-sm text-gray-500">
                        Nama akan ditampilkan sebagai <b>Orang Baik</b>
                    </p>
                </div>
            </div>

            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" name="anonim" value="1"
                    class="sr-only peer"
                    @change="
                        anonim = !anonim;
                        if (anonim) {
                            namaBackup = nama;
                            nama = 'Orang Baik';
                        } else {
                            nama = namaBackup || '{{ Auth::check() ? Auth::user()->name : '' }}';
                        }
                    ">
                <div class="w-12 h-6 bg-gray-300 rounded-full peer peer-checked:bg-green-500 transition"></div>
                <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition peer-checked:translate-x-6"></div>
            </label>
        </div>

        <!-- Telepon -->
        <input type="text"
            name="telepon"
            x-model="telepon"
            class="w-full border rounded-lg p-3"
            placeholder="No Whatsapp / Handphone"
            required>

        <!-- Email -->
        <input type="email"
            name="email"
            x-model="email"
            class="w-full border rounded-lg p-3"
            placeholder="Email (opsional)">

        <!-- Doa -->
        <textarea name="deskripsi"
            class="w-full border rounded-lg p-3"
            placeholder="Tulis pesan atau doa (opsional)"></textarea>

        <!-- FOOTER -->
        <div class="pt-4 border-t">
            <button class="w-full py-3 rounded-lg bg-green-600 text-white font-semibold">
                Infaq - Rp <span x-text="Number(nominal).toLocaleString('id-ID')"></span>
            </button>
        </div>

    </form>
</div>

</body>
</html>
