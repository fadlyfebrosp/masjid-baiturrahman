<footer class="fixed bottom-0 left-0 w-full bg-white border-t shadow md:hidden z-50"
        x-data="{ openProgram:false, openUser:false }">

    <div class="flex justify-around py-2">

        <!-- BERANDA -->
        <a href="{{ url('/') }}" class="flex flex-col items-center text-xs">
            <i class="bi bi-house-door text-xl"></i>
            Beranda
        </a>

        <!-- TENTANG -->
        <a href="{{ url('/tentangkami') }}" class="flex flex-col items-center text-xs">
            <i class="bi bi-info-circle text-xl"></i>
            Tentang
        </a>

        <!-- PROGRAM -->
        <div class="relative">
            <button @click="openProgram=!openProgram"
                    class="flex flex-col items-center text-xs">
                <i class="bi bi-journal-text text-xl"></i>
                Program
            </button>

            <div x-show="openProgram" x-transition
                 class="absolute bottom-12 left-1/2 -translate-x-1/2 bg-white shadow rounded w-36">
                @foreach ($kategoriProgram as $kat)
                    <a href="{{ url('/program/'.strtolower($kat)) }}"
                       class="block px-4 py-2 text-sm hover:bg-green-50">
                        {{ $kat }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- BERITA -->
        <a href="{{ url('/berita') }}" class="flex flex-col items-center text-xs">
            <i class="bi bi-newspaper text-xl"></i>
            Berita
        </a>

        <!-- GUEST -->
        @guest
            <a href="{{ route('login') }}" class="flex flex-col items-center text-xs">
                <i class="bi bi-person-circle text-xl"></i>
                Login
            </a>
        @endguest

        <!-- AUTH -->
        @auth
            <div class="relative">
                <button @click="openUser=!openUser"
                        class="flex flex-col items-center text-xs">
                    <img alt=""
                        src="{{ Auth::user()->image
                            ? asset('storage/'.Auth::user()->image)
                            : asset('assets/img/admin.jpg') }}"
                        class="w-6 h-6 rounded-full object-cover border"
                    >
                    {{ Str::limit(Auth::user()->name, 6) }}
                </button>

                <div x-show="openUser" x-transition
                     class="absolute bottom-12 right-0 bg-white shadow rounded w-44">

                    {{-- DASHBOARD SESUAI ROLE --}}
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                           class="block px-4 py-2 text-sm hover:bg-green-50">
                            <i class="bi bi-speedometer2 mr-1"></i> Dashboard
                        </a>
                    @elseif(Auth::user()->role === 'finance')
                        <a href="{{ route('finance.dashboard') }}"
                           class="block px-4 py-2 text-sm hover:bg-green-50">
                            <i class="bi bi-speedometer2 mr-1"></i> Dashboard
                        </a>
                    @endif

                    <a href="{{ route('profile') }}"
                       class="block px-4 py-2 text-sm hover:bg-green-50">
                        <i class="bi bi-person mr-1"></i> Profil
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            class="w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50">
                            <i class="bi bi-box-arrow-right mr-1"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        @endauth

    </div>
</footer>
