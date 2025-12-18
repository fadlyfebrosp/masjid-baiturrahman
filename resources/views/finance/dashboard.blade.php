@extends('finance.components.app')

@section('title', 'Dashboard Finance')

@section('content')
<!-- Judul Halaman -->
<div class="mb-6">
  <div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-green-700">Dashboard</h1>

    <div class="text-sm text-gray-600">
        <a href="dashboard" class="hover:underline">Home</a>
        <span class="mx-1">></span>
        <span class="font-semibold text-gray-800">Dashboard</span>
    </div>
  </div>

  <!-- Banner Selamat Datang -->
  <div class="flex items-center bg-green-600 text-white text-sm font-semibold px-4 py-3 rounded-lg shadow-md" role="alert">
    <svg class="fill-current w-5 h-5 mr-3 text-white/90" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path d="M12.432 0c1.34 0 2.01.912 2.01 1.957 0 1.305-1.164 2.512-2.679 2.512-1.269 0-2.009-.75-1.974-1.99C9.789 1.436 10.67 0 12.432 0zM8.309 20c-1.058 0-1.833-.652-1.093-3.524l1.214-5.092c.211-.814.246-1.141 0-1.141-.317 0-1.689.562-2.502 1.117l-.528-.88c2.572-2.186 5.531-3.467 6.801-3.467 1.057 0 1.233 1.273.705 3.23l-1.391 5.352c-.246.945-.141 1.271.106 1.271.317 0 1.357-.392 2.379-1.207l.6.814C12.098 19.02 9.365 20 8.309 20z"/>
    </svg>
    <p class="text-base">Selamat datang <span class="font-bold">{{ Auth::user()->name }}</span> di Dashboard Finance <span class="font-bold">Masjid Baiturrahman</span></p>
  </div>
</div>

<!-- Kartu Statistik -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
  <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
    <h2 class="text-lg font-semibold mb-2 text-green-700">Total Donasi</h2>
    <p class="text-2xl font-bold">Rp 10.500.000</p>
  </div>

  <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
    <h2 class="text-lg font-semibold mb-2 text-green-700">Program Aktif</h2>
    <p class="text-2xl font-bold">12</p>
  </div>

  <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
    <h2 class="text-lg font-semibold mb-2 text-green-700">Konsultasi Masuk</h2>
    <p class="text-2xl font-bold">8</p>
  </div>
</div>
@endsection
