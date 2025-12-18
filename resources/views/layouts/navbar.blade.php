<!-- NAVBAR -->
<nav class="bg-white shadow-sm fixed w-full top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">

        <!-- LOGO -->
        <a href="{{ url('/') }}" class="flex items-center space-x-2">
            <img src="{{ $logo }}" class="w-20 h-14 object-contain" alt="Logo">
        </a>

        <!-- SEARCH MOBILE -->
        <div class="flex md:hidden items-center w-2/3">
            <div class="relative w-full">
                <input
                    type="text"
                    placeholder="Cari Program..."
                    class="w-full border border-green-600 rounded-full py-2 pl-4 pr-10 text-sm focus:ring-2 focus:ring-green-600"
                >
                <i class="bi bi-search absolute right-3 top-1/2 -translate-y-1/2 text-green-700"></i>
            </div>
        </div>

        <!-- MENU DESKTOP -->
        <ul class="hidden md:flex items-center space-x-6 text-gray-700 font-medium">
            <li>
                <a href="{{ url('/') }}"
                   class="{{ request()->is('/') ? 'text-green-700 font-semibold border-b-2 border-green-700' : '' }}">
                    Beranda
                </a>
            </li>

            <li>
                <a href="{{ url('/tentangkami') }}"
                   class="{{ request()->is('tentangkami') ? 'text-green-700 font-semibold border-b-2 border-green-700' : '' }}">
                    Tentang Kami
                </a>
            </li>

            <!-- PROGRAM DROPDOWN -->
            <li class="relative" x-data="{ open: false }" @mouseenter="open=true" @mouseleave="open=false">
                <button class="flex items-center gap-1 hover:text-green-700">
                    Program <i class="bi bi-chevron-down text-xs"></i>
                </button>

                <div x-show="open" x-transition
                     class="absolute mt-2 w-52 bg-white shadow rounded-lg py-2">
                    @foreach ($kategoriProgram as $kat)
                        <a href="{{ url('/program/'.strtolower($kat)) }}"
                           class="block px-4 py-2 text-sm hover:bg-green-50 hover:text-green-700">
                            {{ $kat }}
                        </a>
                    @endforeach
                </div>
            </li>

            <li>
                <a href="{{ url('/berita') }}"
                   class="{{ request()->is('berita*') ? 'text-green-700 font-semibold border-b-2 border-green-700' : '' }}">
                    Berita & Kegiatan
                </a>
            </li>

            <li>
                <a href="{{ url('/kontak') }}"
                   class="{{ request()->is('kontak') ? 'text-green-700 font-semibold border-b-2 border-green-700' : '' }}">
                    Hubungi Kami
                </a>
            </li>
        </ul>

        <!-- SEARCH + USER -->
        <div class="hidden md:flex items-center space-x-4">

            <!-- SEARCH -->
            <div class="relative">
                <input
                    type="text"
                    placeholder="Cari Program..."
                    class="border border-green-600 rounded-full px-4 py-2 pr-10 text-sm focus:ring-2 focus:ring-green-600"
                >
                <i class="bi bi-search absolute right-3 top-1/2 -translate-y-1/2 text-green-700"></i>
            </div>

            <!-- GUEST -->
            @guest
                <a href="{{ route('login') }}"
                   class="bg-green-700 text-white px-4 py-2 rounded-full hover:bg-green-800">
                    Login
                </a>
            @endguest

            <!-- AUTH -->
            @auth
                <div class="relative" x-data="{ open:false }">
                    <button @click="open=!open" class="flex items-center gap-2">
                        <img alt=""
                            src="{{ Auth::user()->image
                                ? asset('storage/'.Auth::user()->image)
                                : asset('assets/img/admin.jpg') }}"
                            class="w-8 h-8 rounded-full border object-cover"
                        >
                        <span class="font-medium">{{ Auth::user()->name }}</span>
                        <i class="bi bi-chevron-down text-xs"></i>
                    </button>

                    <div x-show="open" @click.away="open=false" x-transition
                         class="absolute right-0 mt-2 w-52 bg-white shadow rounded-lg py-2">

                        {{-- DASHBOARD SESUAI ROLE --}}
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}"
                               class="block px-4 py-2 text-sm hover:bg-green-50">
                                <i class="bi bi-speedometer2 mr-2"></i> Dashboard Admin
                            </a>
                        @elseif(Auth::user()->role === 'finance')
                            <a href="{{ route('finance.dashboard') }}"
                               class="block px-4 py-2 text-sm hover:bg-green-50">
                                <i class="bi bi-speedometer2 mr-2"></i> Dashboard Finance
                            </a>
                        @endif

                        <a href="{{ route('profile') }}"
                           class="block px-4 py-2 text-sm hover:bg-green-50">
                            <i class="bi bi-person mr-2"></i> Profil Saya
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                class="w-full text-left px-4 py-2 text-sm hover:bg-red-50 text-red-600">
                                <i class="bi bi-box-arrow-right mr-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</nav>
