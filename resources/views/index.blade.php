@extends('layouts.app')

@section('title', 'Masjid Baiturrahman | Pusat Ibadah, Donasi & Kegiatan Umat')

@section('meta_description')
Masjid Baiturrahman sebagai pusat ibadah, dakwah, donasi, zakat, dan kegiatan keislaman umat.
@endsection

@section('meta_keywords')
Masjid Baiturrahman, masjid, donasi masjid, zakat, infaq, kegiatan islam
@endsection

@section('meta_image', asset('assets/img/logo1.png'))

@section('content')
<!-- ========================== -->
<!-- 🔝 Hero Section -->
<!-- ========================== -->
@include('sections.hero')

<!-- ========================== -->
<!-- 📢 Program Section -->
<!-- ========================== -->
@include('sections.program')


<!-- ========================== -->
<!-- 💬 Konsultasi Section -->
<!-- ========================== -->
@include('sections.konsultasi')

<!-- ========================== -->
<!-- 💝 Donasi Pilihan -->
<!-- ========================== -->
@include('sections.donasi-pilihan')

<!-- ========================== -->
<!-- 💰 Donasi ZISWAF Section -->
<!-- ========================== -->
@include('sections.donasi-ziswaf')

<!-- ========================== -->
<!-- 📰 Berita Section -->
<!-- ========================== -->
@include('sections.berita', ['data' => $berita])

<!-- ========================== -->
<!-- 📊 Laporan Section -->
<!-- ========================== -->
@include('sections.laporan')
@endsection
