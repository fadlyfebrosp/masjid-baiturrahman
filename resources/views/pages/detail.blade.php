@extends('layouts.app')

@section('title', $berita->judul . ' - Masjid Baiturrahman')

@section('content')
<!-- Hero Section -->
<div class="bg-green-50 py-20 relative overflow-hidden">
  <div class="container mx-auto px-6 md:px-12">
    <h1 class="text-3xl font-bold text-gray-800 ml-10">{{ $berita->kategori }}</h1>
  </div>
</div>

<!-- Detail Berita -->
<section class="bg-white py-16">
  <div class="container mx-auto px-6 md:px-12">
    <div class="grid md:grid-cols-4 gap-10">

      <!-- Konten Utama -->
      <div class="md:col-span-3">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2 leading-snug">
          {{ $berita->judul }}
        </h2>

        <div class="flex items-center text-gray-500 text-sm space-x-4 mb-6">
          <span><i class="bi bi-person-circle"></i> {{ $berita->namamasjid }}</span>
          <span><i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::parse($berita->tanggal)->translatedFormat('d F Y') }}</span>
          <span><i class="bi bi-folder2-open"></i> {{ $berita->kategori }}</span>
        </div>

        @if ($berita->foto)
          <img src="{{ asset('storage/' . $berita->foto) }}" alt="{{ $berita->judul }}"
          class="block w-full max-w-4xl rounded-xl shadow mb-8 object-cover h-[480px]"
          >
        @endif

        <div class="space-y-5 text-gray-700 leading-relaxed text-justify">
          {!! nl2br(e($berita->deskripsi)) !!}
        </div>

        <!-- Bagikan -->
        <div class="mt-10 border-t pt-6 flex items-center space-x-4 text-gray-600">
          <span>Bagikan:</span>
          <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank" class="text-green-600 hover:text-green-800"><i class="bi bi-facebook text-xl"></i></a>
          <a href="https://wa.me/?text={{ urlencode(request()->fullUrl()) }}" target="_blank" class="text-green-600 hover:text-green-800"><i class="bi bi-whatsapp text-xl"></i></a>
          <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}" target="_blank" class="text-green-600 hover:text-green-800"><i class="bi bi-twitter text-xl"></i></a>
          <a href="mailto:?subject={{ urlencode($berita->judul) }}&body={{ urlencode(request()->fullUrl()) }}" class="text-green-600 hover:text-green-800"><i class="bi bi-envelope-fill text-xl"></i></a>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="space-y-5">
        <div class="bg-white shadow rounded-xl p-6">
          <h3 class="font-bold text-green-700 mb-4">Berita Lainnya</h3>
          <ul class="space-y-3 text-gray-700 text-sm">
            @foreach ($beritaLainnya as $lainnya)
              <li>
                  <a href="{{ url('/berita/' . urlencode($lainnya->judul)) }}" class="hover:text-green-600">
                  {{ $lainnya->judul }}
                </a>
              </li>
            @endforeach
          </ul>
        </div>
      </div>

    </div>
  </div>
</section>
@endsection
