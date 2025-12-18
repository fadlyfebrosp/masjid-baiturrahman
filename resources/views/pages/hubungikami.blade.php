@extends('layouts.app')

@section('title', 'Hubungi Kami - Masjid Baiturrahman')

@section('content')
<!-- Hero Section -->
<div class="bg-green-50 py-20">
  <div class="container mx-auto px-6 md:px-12">
    <h1 class="text-3xl font-bold text-gray-800">
        Hubungi Kami
    </h1>
  </div>
</div>

<!-- Contact Section -->
<section class="bg-white py-16">
  <div class="container mx-auto px-6 md:px-12">
    <div class="grid md:grid-cols-2 gap-10">

      <!-- Informasi Kontak -->
      <div>
        <h2 class="text-2xl font-bold text-green-700 mb-4">
            Informasi Kontak
        </h2>

        <p class="text-gray-700 mb-6">
            Kami siap melayani semua pertanyaan dan kebutuhan Anda. Silakan hubungi kami melalui kontak di bawah atau isi formulir.
        </p>

        <ul class="space-y-4 text-gray-700">

          <!-- Alamat -->
          <li class="flex items-start gap-3">
            <i class="bi bi-geo-alt-fill text-green-600 text-xl"></i>
            <span>
                {{ $kontak->alamat_lengkap ?? '-' }}
            </span>
          </li>

          <!-- Email -->
          @if(!empty($kontak->email))
          <li class="flex items-center gap-3">
            <i class="bi bi-envelope-fill text-green-600 text-xl"></i>
            <a href="mailto:{{ $kontak->email }}" class="hover:text-green-700">
                {{ $kontak->email }}
            </a>
          </li>
          @endif

          <!-- Telepon -->
          @if(!empty($kontak->nomor_telepon))
          <li class="flex items-center gap-3">
            <i class="bi bi-telephone-fill text-green-600 text-xl"></i>
            <a href="tel:{{ preg_replace('/[^0-9]/', '', $kontak->nomor_telepon) }}"
               class="hover:text-green-700">
                {{ $kontak->nomor_telepon }}
            </a>
          </li>
          @endif

          <!-- WhatsApp -->
          <li class="flex items-center gap-3">
            <i class="bi bi-whatsapp text-green-600 text-xl"></i>
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $kontak->nomor_whatsapp ?? '') }}"
               target="_blank"
               class="hover:text-green-700">
                {{ $kontak->nomor_whatsapp ?? '-' }}
            </a>
          </li>

        </ul>
      </div>

      <!-- Formulir Kontak -->
      <div class="bg-green-50 p-8 rounded-xl shadow">
        <!-- Notifikasi sukses -->
        @if(session('success'))
            <div class="bg-green-200 text-green-800 p-3 rounded mb-5">
                {{ session('success') }}
            </div>
        @endif
        <!-- Notifikasi error -->
        @if($errors->any())
            <div class="bg-red-200 text-red-800 p-3 rounded mb-5">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('kontak.send') }}" method="POST" class="space-y-5">
          @csrf

          <div>
            <label for="" class="block font-semibold mb-1">Nama</label>
            <input type="text" name="nama" value="{{ old('nama') }}"
              class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-green-500">
          </div>

          <div>
            <label for="" class="block font-semibold mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}"
              class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-green-500">
          </div>

          <div>
            <label for="" class="block font-semibold mb-1">No. Telp / WhatsApp</label>
            <input type="text" name="no_telp" value="{{ old('no_telp') }}"
              class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-green-500">
          </div>

          <div>
            <label for="" class="block font-semibold mb-1">Subject</label>
            <input type="text" name="judul" value="{{ old('judul') }}"
              class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-green-500">
          </div>

          <div>
            <label for="" class="block font-semibold mb-1">Pesan</label>
            <textarea rows="5" name="pesan"
              class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-green-500">{{ old('pesan') }}</textarea>
          </div>

          <button
            class="bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700">
            Kirim Pesan
          </button>

        </form>
      </div>
    </div>

    <!-- Google Map -->
    <div class="mt-12 rounded-xl overflow-hidden shadow-lg">
        <iframe
            title="Peta lokasi Masjid Baiturrahman"
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.123456789!2d106.764328!3d-6.5825866!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69c4fbfb42dc27%3A0xdc4d107cfcd1f057!2sMasjid%20Baiturrahman!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid"
            width="100%"
            height="400"
            style="border:0;"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </div>
  </div>
</section>
@endsection
