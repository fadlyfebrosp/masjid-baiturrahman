<nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
    <!-- TITLE -->
    <h1 class="text-xl font-semibold text-green-700">
        {{ $appName }}
    </h1>

    <!-- RIGHT MENU -->
    <div class="flex items-center space-x-4">

        <!-- PROFILE DROPDOWN -->
        <div x-data="{ open: false }" class="relative">
            <button
                @click="open = !open"
                class="flex items-center space-x-2 focus:outline-none"
            >
                <img
                    src="{{ Auth::user()->image
                        ? asset('storage/' . Auth::user()->image)
                        : asset('assets/img/admin.jpg') }}"
                    class="w-8 h-8 rounded-full object-cover"
                    alt="Profile"
                >

                <span class="text-sm font-medium text-gray-700">
                    {{ Auth::user()->name }}
                </span>

                <i class="bi bi-caret-down-fill text-gray-500 text-xs"></i>
            </button>

            <!-- DROPDOWN CARD -->
            <div
                x-show="open"
                x-transition
                @click.outside="open = false"
                class="absolute right-0 mt-3 w-72 bg-white rounded-xl shadow-xl overflow-hidden z-50"
            >

                <!-- HEADER -->
                <div class="bg-green-600 text-white text-center p-4">
                    <img
                        src="{{ Auth::user()->image
                            ? asset('storage/' . Auth::user()->image)
                            : asset('assets/img/admin.jpg') }}"
                        class="w-24 h-24 rounded-full mx-auto border-4 border-white object-cover"
                        alt=""
                    >

                    <h3 class="mt-3 text-lg font-semibold flex justify-center items-center gap-1">
                        {{ Auth::user()->name }}

                        @if(Auth::user()->role === 'admin')
                            <i class="bi bi-patch-check-fill text-white text-sm"></i>
                        @endif
                    </h3>

                    <p class="text-sm mt-2">
                        Tanggal Bergabung :
                        {{ Auth::user()->created_at->format('d-m-Y') }}
                    </p>

                    <p class="text-sm">
                        Terakhir Login :
                        {{ Auth::user()->last_login_at
                            ? Auth::user()->last_login_at->format('d-m-Y ( H:i:s )')
                            : '-' }}
                    </p>
                </div>

                <!-- BODY -->
                <div class="p-3 border-t space-y-1">
                    <a
                        href="{{ route('home') }}"
                        class="flex items-center px-3 py-2 text-sm rounded-md hover:bg-gray-100 transition"
                    >
                        <i class="bi bi-house-door me-2"></i>
                        Kembali ke Home
                    </a>
                </div>

                <!-- FOOTER -->
                <div class="p-4 bg-gray-50 border-t">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button
                            type="submit"
                            class="w-full flex items-center justify-center gap-2 border border-gray-300 rounded-md py-2 text-gray-700 hover:bg-red-500 hover:text-white transition"
                        >
                            <i class="bi bi-box-arrow-right"></i>
                            Logout
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</nav>
