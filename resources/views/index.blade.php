@extends('layouts.app')

@section('title')
Masjid Baiturrahman Sindang Barang | Pusat Ibadah, Donasi & Kegiatan Umat
@endsection

@section('meta_description')
Masjid Baiturrahman Sindang Barang adalah pusat ibadah, dakwah, donasi, zakat, infaq, serta kegiatan sosial umat Islam di Sindang Barang.
@endsection

@section('meta_keywords')
Masjid Baiturrahman Sindang Barang, masjid sindang barang, masjid baiturrahman, donasi masjid, zakat, infaq, kegiatan islam
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
