@extends('layouts.app')

@section('title', 'Tentang Kami - Masjid Baiturrahman')

@section('content')
<!-- Hero Section -->
    <div class="bg-green-50 py-20 relative overflow-hidden">
        <div class="relative container mx-auto px-6 md:px-12">
            <h1 class="text-3xl font-bold text-gray-800 ml-10">Tentang Kami</h1>
        </div>
    </div>

<!-- Main Content -->
<section class="bg-white-50 py-16">
  <div class="container mx-auto px-6 md:px-12">
    <div class="grid md:grid-cols-4 gap-8">
        <!-- Sidebar -->
        <div
        x-data="{ open: true }"
        class="bg-white shadow rounded-xl p-6 h-fit md:sticky md:top-10 md:self-start"
        >
        <!-- Tombol utama -->
        <button
            @click="open = !open"
            class="bg-white flex justify-between items-center w-full text-left font-semibold text-gray-800 hover:text-green-700 focus:outline-none"
        >
            <span class="text-green-700">Tentang Kami</span>
            <svg
            :class="open ? 'rotate-180 text-green-600' : 'rotate-0'"
            xmlns="http://www.w3.org/2000/svg"
            class="w-5 h-5 transform transition-transform duration-200"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <!-- Dropdown isi -->
        <div x-show="open" x-transition class="mt-4 border-t pt-3">
            <ul class="space-y-2 text-gray-700 text-sm">
            <li>
                <a href="#tentang"
                @click.prevent="document.querySelector('#tentang').scrollIntoView({ behavior: 'smooth' })"
                class="hover:text-green-600 cursor-pointer">
                1. Tentang Masjid Baiturrahman
                </a>
            </li>
            <li>
                <a href="#tujuan"
                @click.prevent="document.querySelector('#tujuan').scrollIntoView({ behavior: 'smooth' })"
                class="hover:text-green-600 cursor-pointer">
                2. Tujuan
                </a>
            </li>
            <li>
                <a href="#peran"
                @click.prevent="document.querySelector('#peran').scrollIntoView({ behavior: 'smooth' })"
                class="hover:text-green-600 cursor-pointer">
                3. Peran dan Fungsi
                </a>
            </li>
            </ul>
        </div>
        </div>


        <!-- Content -->
        <div class="md:col-span-3 space-y-10">
        <!-- Tentang -->
        <div id="tentang" class="bg-white rounded-xl shadow p-6">
            <h3 class="text-xl font-bold text-green-700 mb-4">Tentang Masjid Baiturrahman</h3>
            <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                <!-- Gambar -->
                <img src="{{ asset('assets/img/logo1.png') }}" alt="Masjid Baiturrahman"
                    class="w-32 h-32 object-contain md:w-40 md:h-40">

                <!-- Deskripsi -->
                <p class="text-gray-700 leading-relaxed text-lg md:text-xl text-justify">
                Masjid Baiturrahman merupakan salah satu sarana ibadah yang berfungsi
                sebagai tempat pembinaan umat dan wadah kegiatan keagamaan. Masjid ini berdiri
                sebagai pusat kegiatan dakwah, pendidikan, serta sosial yang melibatkan seluruh
                lapisan masyarakat sekitar.
                </p>
            </div>
        </div>

        <!-- Tujuan -->
        <div id="tujuan" class="bg-white rounded-xl shadow p-6">
            <h3 class="text-xl font-bold text-green-700 mb-4">Tujuan</h3>
            <p class="text-gray-700 mb-3">
            Tujuan Masjid Baiturrahman antara lain:
            </p>
            <ul class="pl-2 text-gray-700 space-y-3 bg-green-50 rounded-lg p-5">
                <li class="flex items-start gap-2">
                    <i class="bi bi-check-circle-fill text-green-600 relative top-0.5"></i>
                    <span>Mengembangkan dan meningkatkan pembinaan umat dalam rangka turut aktif beribadah.</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="bi bi-check-circle-fill text-green-600 relative top-0.5"></i>
                    <span>Meningkatkan peran masjid sebagai wadah pemersatu umat dan pusat ibadah.</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="bi bi-check-circle-fill text-green-600 relative top-0.5"></i>
                    <span>Meningkatkan kualitas umat Islam dan ketaqwaan kepada Allah SWT.</span>
                </li>
            </ul>
        </div>

        <!-- Peran dan Fungsi -->
        <div id="peran" class="bg-white rounded-xl shadow p-6">
            <h3 class="text-xl font-bold text-green-700 mb-4">Peran dan Fungsi</h3>
            <p class="text-gray-700 mb-3">
            Masjid Baiturrahman berperan sebagai pusat kegiatan dan informasi umat Islam,
            baik bagi masyarakat sekitar maupun warga kampus.
            </p>
            <ul class="list-decimal pl-6 text-gray-700 space-y-2">
            <li>Pusat ibadah dalam rangka mewujudkan hubungan manusia dengan Allah.</li>
            <li>Wadah dakwah Islamiyah, baik bil hal, bil lisan, dan bil kitabah.</li>
            <li>Wadah pembinaan sosial, politik, hukum, dan budaya masyarakat Islam.</li>
            <li>Wadah kegiatan keislaman dan dakwah bagi generasi muda Islam.</li>
            <li>Wadah pembinaan ketahanan fisik dan mental masyarakat.</li>
            <li>Wadah penyaluran peran serta dalam pembinaan generasi Islam.</li>
            <li>Wadah penerangan dan informasi keislaman.</li>
            <li>Wadah untuk memperkuat ukhuwah umat berlandaskan syari’ah.</li>
            </ul>
        </div>
        </div>
    </div>
  </div>
</section>
@endsection
