@extends('layouts.app')

@section('title', 'Hubungi Kami | Masjid Baiturrahman')

@section('meta_description')
Hubungi Masjid Baiturrahman untuk informasi kegiatan, donasi, zakat, dan kerja sama.
@endsection

@section('meta_keywords')
kontak masjid, hubungi masjid, masjid baiturrahman
@endsection

@section('content')
<!-- HERO -->
<section class="relative bg-gradient-to-br from-green-600 to-green-800 text-white overflow-hidden">
    <div class="container mx-auto px-6 md:px-12 py-24">

        <!-- Breadcrumb -->
        <nav class="text-sm opacity-90 mb-3">
            <a href="{{ url('/') }}" class="hover:underline">Beranda</a>
            <span class="mx-2">/</span>
            <span class="font-medium">Berita & Kegiatan</span>
        </nav>

        <!-- Title -->
        <h1 class="text-4xl md:text-5xl font-bold leading-tight">
            Berita & Kegiatan
        </h1>

        <!-- Subtitle -->
        <p class="mt-4 max-w-2xl text-lg text-green-100">
            Informasi dan dokumentasi kegiatan Masjid Baiturrahman.
        </p>

    </div>

    <!-- Decorative blur -->
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-green-400 opacity-20 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-green-300 opacity-20 rounded-full blur-3xl"></div>
</section>
<!-- Contact Section -->
<section class="bg-white py-16">
  <div class="container mx-auto px-6 md:px-12">

    <div class="grid md:grid-cols-2 gap-10">
      <!-- Informasi Kontak -->
      <div>
        <h2 class="text-2xl font-bold text-green-700 mb-4">Informasi Kontak</h2>
        <p class="text-gray-700 mb-6">
          Kami siap melayani semua pertanyaan dan kebutuhan Anda.
        </p>

        <ul class="space-y-4 text-gray-700">
          <li class="flex items-start gap-3">
            <i class="bi bi-geo-alt-fill text-green-600 text-xl"></i>
            <span>{{ $kontak->alamat_lengkap ?? '-' }}</span>
          </li>

          @if(!empty($kontak->email))
          <li class="flex items-center gap-3">
            <i class="bi bi-envelope-fill text-green-600 text-xl"></i>
            <a href="mailto:{{ $kontak->email }}">{{ $kontak->email }}</a>
          </li>
          @endif

          @if(!empty($kontak->nomor_telepon))
          <li class="flex items-center gap-3">
            <i class="bi bi-telephone-fill text-green-600 text-xl"></i>
            <a href="tel:{{ preg_replace('/[^0-9]/', '', $kontak->nomor_telepon) }}">
              {{ $kontak->nomor_telepon }}
            </a>
          </li>
          @endif

          <li class="flex items-center gap-3">
            <i class="bi bi-whatsapp text-green-600 text-xl"></i>
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $kontak->nomor_whatsapp ?? '') }}" target="_blank">
              {{ $kontak->nomor_whatsapp ?? '-' }}
            </a>
          </li>
        </ul>
      </div>

      <!-- Form -->
      <div class="bg-green-50 p-8 rounded-xl shadow">
        <form action="{{ route('kontak.send') }}" method="POST" class="space-y-5">
          @csrf
          <input type="text" name="nama" placeholder="Nama" class="w-full border p-3 rounded-lg">
          <input type="email" name="email" placeholder="Email" class="w-full border p-3 rounded-lg">
          <input type="text" name="no_telp" placeholder="No. Telp / WhatsApp" class="w-full border p-3 rounded-lg">
          <input type="text" name="judul" placeholder="Subject" class="w-full border p-3 rounded-lg">
          <textarea name="pesan" rows="5" placeholder="Pesan" class="w-full border p-3 rounded-lg"></textarea>
          <button class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700">
            Kirim Pesan
          </button>
        </form>
      </div>
    </div>
    <!-- GOOGLE MAP IFRAME + OVERLAY TRANSPARAN -->
    <div class="mt-12 rounded-xl overflow-hidden shadow-lg relative group">

    <!-- MAP -->
    <iframe
        title="Peta Lokasi Masjid Baiturrahman"
        src="https://www.google.com/maps?q=-6.5825866,106.764328&hl=id&z=17&t=h&output=embed"
        width="100%"
        height="400"
        class="pointer-events-none"
        style="border:0;"
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade">
    </iframe>

    <!-- OVERLAY -->
    <a
        href="https://www.google.com/maps/@?api=1&map_action=pano&viewpoint=-6.5825866,106.764328"
        target="_blank"
        class="absolute inset-0 z-10 flex items-center justify-center
            bg-black/0 hover:bg-black/20
            transition-all duration-300"
        title="Lihat Google Street View"
    >
        <div class="opacity-0 group-hover:opacity-100
                    transform translate-y-2 group-hover:translate-y-0
                    transition-all duration-300
                    bg-white/90 backdrop-blur
                    px-6 py-3 rounded-xl shadow-lg
                    flex items-center gap-2
                    font-semibold text-gray-800">
        👁️ Lihat Street View
        </div>
    </a>

    </div>
  </div>
</section>
@endsection
