@extends('layouts.app')

@section('title', 'Tentang Kami | Masjid Baiturrahman')

@section('meta_description')
Masjid Baiturrahman adalah pusat ibadah, pembinaan umat, dan kepedulian sosial yang dikelola oleh Dewan Kemakmuran Masjid.
@endsection

@section('meta_keywords')
tentang kami masjid, struktur dkm, pengurus masjid, masjid baiturrahman
@endsection
<style>
/* =====================
   TYPOGRAPHY SYSTEM
   ===================== */

.section-title {
    font-size: 1.5rem;        /* 24px */
    font-weight: 700;
    color: #047857;
    margin-bottom: 1rem;
    letter-spacing: -0.01em;
}

h3 {
    font-size: 1.125rem;      /* 18px */
    font-weight: 600;
    color: #047857;
}

p, li {
    font-size: 0.95rem;       /* 15px */
    line-height: 1.7;
    color: #374151;
}

/* =====================
   CARD & BOX
   ===================== */

.card {
    background: white;
    padding: 32px;
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0,0,0,.05);
}

.box {
    background: #ecfdf5;
    padding: 12px 14px;
    border-radius: 10px;
    font-size: 0.9rem;
    font-weight: 500;
}

/* =====================
   ORGANIZATION STRUCTURE
   ===================== */

.org-box {
    background: #ecfdf5;
    border: 1px solid #bbf7d0;
    border-radius: 14px;
    padding: 16px;
    text-align: center;
    box-shadow: 0 6px 15px rgba(0,0,0,.04);
}

.org-title {
    display: block;
    font-size: 0.95rem;
    font-weight: 700;
    color: #047857;
    margin-bottom: 4px;
}

.org-name {
    display: block;
    font-size: 0.9rem;
    font-weight: 600;
    color: #065f46;
}

.org-sub {
    display: block;
    font-size: 0.85rem;
    color: #374151;
}

/* =====================
   NAV / SIDEBAR
   ===================== */

aside a {
    font-size: 0.9rem;
}
</style>
@section('content')

<!-- HERO -->
<section class="relative bg-gradient-to-br from-green-600 to-green-800 text-white">
    <div class="container mx-auto px-6 md:px-12 py-24">
        <nav class="text-sm mb-4 opacity-90">
            <a href="{{ url('/') }}" class="hover:underline">Beranda</a>
            <span class="mx-2">/</span>
            <span class="font-semibold">Tentang Kami</span>
        </nav>

        <h1 class="text-4xl md:text-5xl font-bold leading-tight">
            Masjid, Umat, dan Amanah
        </h1>
        <p class="mt-5 max-w-2xl text-lg text-green-100">
            Masjid Baiturrahman dikelola dengan amanah melalui struktur Dewan Kemakmuran
            Masjid untuk melayani ibadah, dakwah, dan kepedulian sosial umat.
        </p>
    </div>
</section>

<!-- CONTENT -->
<section class="py-16">
    <div class="container mx-auto px-6 md:px-12">
        <div class="grid md:grid-cols-4 gap-10">

            <!-- SIDEBAR -->
            <aside
                x-data="{ open: true }"
                class="border border-green-300 rounded-2xl overflow-hidden h-fit md:sticky md:top-24 bg-white"
            >

                <!-- HEADER HIJAU -->
                <button
                    @click="open = !open"
                    class="w-full flex items-center justify-between px-5 py-4 bg-green-700 text-white font-semibold"
                >
                    <span>Tentang Kami</span>

                    <svg
                        class="w-5 h-5 transition-transform duration-300"
                        :class="open ? 'rotate-180' : ''"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- ISI ACCORDION -->
                <div x-show="open" x-transition class="px-5 py-4">
                    <ol class="list-decimal pl-4 space-y-3 text-gray-800 text-sm">

                        <li>
                            <a href="#tentang" class="hover:text-green-700 transition">
                                Tentang Masjid Baiturrahman
                            </a>
                        </li>

                        <li>
                            <a href="#nilai" class="hover:text-green-700 transition">
                                Tujuan
                            </a>
                        </li>

                        <li>
                            <a href="#peran" class="hover:text-green-700 transition">
                                Peran dan Fungsi
                            </a>
                        </li>

                        <li>
                            <a href="#struktur" class="hover:text-green-700 transition">
                                Struktur DKM
                            </a>
                        </li>

                    </ol>
                </div>

            </aside>

            <!-- MAIN -->
            <div class="md:col-span-3 space-y-12">

                <!-- TENTANG -->
                <section id="tentang" class="card">
                    <h2 class="section-title">Tentang Masjid Baiturrahman</h2>
                    <p class="text-gray-700 leading-relaxed text-justify">
                        Masjid Baiturrahman hadir sebagai ruang ibadah, pembinaan,
                        dan kebersamaan umat. Setiap aktivitasnya diarahkan untuk
                        memperkuat hubungan kepada Allah SWT sekaligus membangun
                        kepedulian sosial antar sesama.
                    </p>
                </section>

                <!-- NILAI -->
                <section id="nilai" class="card">
                    <h2 class="section-title">Nilai dan Tujuan</h2>
                    <ul class="grid md:grid-cols-2 gap-4 text-gray-700">
                        <li class="box">Spiritualitas dan ketakwaan</li>
                        <li class="box">Edukasi dan pembinaan umat</li>
                        <li class="box">Kepedulian sosial</li>
                        <li class="box">Ukhuwah Islamiyah</li>
                    </ul>
                </section>

                <!-- PERAN -->
                <section id="peran" class="card">
                    <h2 class="section-title">Peran dan Fungsi</h2>
                    <ol class="list-decimal pl-5 text-gray-700 space-y-2">
                        <li>Pusat ibadah dan dakwah</li>
                        <li>Pusat pembinaan generasi muda</li>
                        <li>Pusat kegiatan sosial dan kemasyarakatan</li>
                        <li>Pusat pengelolaan zakat, infak, dan sedekah</li>
                    </ol>
                </section>

                <!-- STRUKTUR DKM -->
                <section id="struktur" class="card">
                    <h2 class="section-title text-center">
                        Struktur Pengurus Dewan Kemakmuran Masjid<br>
                        Masjid Baiturrahman 2024
                    </h2>

                    <!-- LEVEL ATAS -->
                    <div class="flex justify-center mt-10">
                        <div class="org-box">
                            <span class="org-title">Ketua DKM</span>
                            <span class="org-name">Denny Rachmat Mustopa</span>
                        </div>
                    </div>

                    <!-- SEKRETARIS & BENDAHARA -->
                    <div class="flex justify-center gap-10 mt-8 flex-wrap">
                        <div class="org-box">
                            <span class="org-title">Sekretaris</span>
                            <span class="org-name">Denny Herdiansyah</span>
                        </div>

                        <div class="org-box">
                            <span class="org-title">Bendahara</span>
                            <span class="org-name">Agus Ruswandi</span>
                            <span class="org-sub">H. Ruyani</span>
                        </div>
                    </div>

                    <!-- PENASEHAT & PENGAWAS -->
                    <div class="grid md:grid-cols-2 gap-6 mt-10">
                        <div class="org-box">
                            <span class="org-title">Penasehat</span>
                            <span class="org-sub">M. Soleh</span>
                            <span class="org-sub">H. Aan Kardiana</span>
                            <span class="org-sub">H. Ikin</span>
                        </div>

                        <div class="org-box">
                            <span class="org-title">Pengawas</span>
                            <span class="org-sub">Rohim Sugandi</span>
                            <span class="org-sub">Mista</span>
                            <span class="org-sub">H. Mangku</span>
                        </div>
                    </div>

                    <!-- BIDANG-BIDANG -->
                    <div class="mt-12">
                        <h3 class="text-lg font-semibold text-green-700 mb-6 text-center">
                            Bidang & Seksi
                        </h3>

                        <div class="grid md:grid-cols-3 gap-6 text-sm">

                            <div class="org-box">
                                <span class="org-title">Humas</span>
                                <span class="org-sub">Rudi Cahyadi</span>
                                <span class="org-sub">Tri Rujio</span>
                                <span class="org-sub">M. Ali</span>
                                <span class="org-sub">O. Abdurroihim</span>
                            </div>

                            <div class="org-box">
                                <span class="org-title">Dakwah & Tarbiyah</span>
                                <span class="org-sub">H. M. Yusup Walid</span>
                            </div>

                            <div class="org-box">
                                <span class="org-title">Bidang Ibadah</span>
                                <span class="org-sub">Mad Tamim</span>
                                <span class="org-sub">Dede</span>
                                <span class="org-sub">Roni</span>
                                <span class="org-sub">Rosidin</span>
                            </div>

                            <div class="org-box">
                                <span class="org-title">Sosial & Kemasyarakatan</span>
                                <span class="org-sub">Enjang</span>
                                <span class="org-sub">Asep Doni</span>
                            </div>

                            <div class="org-box">
                                <span class="org-title">Pengembangan Bisnis</span>
                                <span class="org-sub">Gunawan</span>
                                <span class="org-sub">Juhana</span>
                            </div>

                            <div class="org-box">
                                <span class="org-title">Infrastruktur & Pemeliharaan</span>
                                <span class="org-sub">Otong Musa</span>
                                <span class="org-sub">Aip Nurohman</span>
                                <span class="org-sub">Herman Hermawan</span>
                                <span class="org-sub">Cece Badrudin</span>
                                <span class="org-sub">Choerulloh</span>
                            </div>

                            <div class="org-box">
                                <span class="org-title">Kepemudaan</span>
                                <span class="org-sub">Awaludin</span>
                                <span class="org-sub">Ganda Supriatna</span>
                                <span class="org-sub">Mahmur</span>
                                <span class="org-sub">Yusep Ilmi</span>
                            </div>

                            <div class="org-box">
                                <span class="org-title">Umahat</span>
                                <span class="org-sub">Ibu Ade</span>
                            </div>

                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>
</section>

<style>
.nav-link {
    display:block;
    padding:8px 12px;
    border-radius:8px;
}
.nav-link:hover {
    background:#ecfdf5;
    color:#047857;
}
.card {
    background:white;
    padding:32px;
    border-radius:16px;
    box-shadow:0 10px 25px rgba(0,0,0,.05);
}
.section-title {
    font-size:1.5rem;
    font-weight:700;
    color:#047857;
    margin-bottom:1rem;
}
.box {
    background:#ecfdf5;
    padding:12px;
    border-radius:10px;
}
.org-box {
    background: #ecfdf5;
    border: 1px solid #bbf7d0;
    border-radius: 14px;
    padding: 16px;
    text-align: center;
    box-shadow: 0 6px 15px rgba(0,0,0,.04);
}
.org-title {
    display: block;
    font-weight: 700;
    color: #047857;
    margin-bottom: 6px;
}
.org-name {
    display: block;
    font-weight: 600;
    color: #065f46;
}
.org-sub {
    display: block;
    color: #374151;
    font-size: 0.85rem;
}
</style>

@endsection
