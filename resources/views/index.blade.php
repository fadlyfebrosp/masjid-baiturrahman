@extends('layouts.app')

@section('title', 'Beranda - Masjid Baiturrahman')

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

<!-- ========================== -->
<!-- 🤝 Mitra Section -->
<!-- ========================== -->
@include('sections.mitra')
@endsection
