<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- =========================
        TITLE
    ========================= --}}
    <title>@yield('title', 'Masjid Baiturrahman')</title>

    {{-- =========================
        SEO META
    ========================= --}}
    <meta name="description"
          content="@yield('meta_description', 'Website resmi Masjid Baiturrahman. Informasi kegiatan, donasi, zakat, dan berita masjid.')">

    <meta name="keywords"
          content="@yield('meta_keywords', 'Masjid Baiturrahman, masjid, donasi, zakat, kegiatan islam')">

    <meta name="author" content="Masjid Baiturrahman">
    <meta name="robots" content="index, follow">

    {{-- =========================
        CANONICAL
    ========================= --}}
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- =========================
        OPEN GRAPH (WA / FB / LINKEDIN)
        INI BAGIAN PALING KRITIS
    ========================= --}}
    <meta property="og:type" content="article">
    <meta property="og:title" content="@yield('title', 'Masjid Baiturrahman')">
    <meta property="og:description"
          content="@yield('meta_description', 'Website resmi Masjid Baiturrahman.')">
    <meta property="og:url" content="{{ url()->current() }}">

    {{-- OG IMAGE --}}
    <meta property="og:image"
          content="@yield('meta_image', asset('assets/img/logo1.png'))">
    <meta property="og:image:secure_url"
          content="@yield('meta_image', asset('assets/img/logo1.png'))">
    <meta property="og:image:type" content="image/jpeg">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    {{-- =========================
        TWITTER CARD
    ========================= --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'Masjid Baiturrahman')">
    <meta name="twitter:description"
          content="@yield('meta_description', 'Website resmi Masjid Baiturrahman.')">
    <meta name="twitter:image"
          content="@yield('meta_image', asset('assets/img/logo1.png'))">

    {{-- =========================
        FAVICON
    ========================= --}}
    <link rel="icon" href="{{ asset('assets/img/icon.png') }}" sizes="48x48">
    <link rel="icon" href="{{ asset('assets/img/icon.png') }}" sizes="192x192">
    <link rel="apple-touch-icon" href="{{ asset('assets/img/icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('assets/img/icon.png') }}">

    {{-- =========================
        STYLES & LIBRARIES
    ========================= --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
          rel="stylesheet">

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- =========================
        CUSTOM STYLE
    ========================= --}}
    <style>
        :root {
            --font-main: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        body {
            font-family: var(--font-main);
            font-size: 16px;
            line-height: 1.7;
            color: #374151;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .boton-elegante {
            padding: 12px 28px;
            border: 2px solid #16a34a;
            background-color: #16a34a;
            color: #ffffff;
            font-size: 1.1rem;
            cursor: pointer;
            border-radius: 30px;
            transition: all 0.4s ease;
            outline: none;
            position: relative;
            overflow: hidden;
            font-weight: bold;
        }

        .boton-elegante::after {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(
                circle,
                rgba(255, 255, 255, 0.25) 0%,
                rgba(255, 255, 255, 0) 70%
            );
            transform: scale(0);
            transition: transform 0.5s ease;
        }

        .boton-elegante:hover::after {
            transform: scale(4);
        }

        .boton-elegante:hover {
            border-color: #15803d;
            background: #15803d;
        }
    </style>
</head>

<body class="antialiased bg-white">

    {{-- =========================
        NAVBAR
    ========================= --}}
    @include('layouts.navbar')

    {{-- =========================
        MAIN CONTENT
    ========================= --}}
    <main class="pt-20 pb-20 min-h-screen">
        @yield('content')
    </main>

    {{-- =========================
        BOTTOM NAV & FOOTER
    ========================= --}}
    @include('layouts.bottomnav')
    @include('layouts.footer')

    {{-- =========================
        JS EXTRA
    ========================= --}}
    <script>
        const carousel = document.getElementById('carousel');
        const scrollLeftBtn = document.getElementById('scrollLeft');
        const scrollRightBtn = document.getElementById('scrollRight');

        if (carousel && scrollLeftBtn && scrollRightBtn) {
            scrollLeftBtn.addEventListener('click', () => {
                carousel.scrollBy({ left: -300, behavior: 'smooth' });
            });

            scrollRightBtn.addEventListener('click', () => {
                carousel.scrollBy({ left: 300, behavior: 'smooth' });
            });

            setInterval(() => {
                carousel.scrollBy({ left: 300, behavior: 'smooth' });
                if (carousel.scrollLeft + carousel.clientWidth >= carousel.scrollWidth) {
                    carousel.scrollTo({ left: 0, behavior: 'smooth' });
                }
            }, 3000);
        }
    </script>

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-out-cubic',
            once: false,
            mirror: true,
            offset: 120
        });
    </script>

</body>
</html>
