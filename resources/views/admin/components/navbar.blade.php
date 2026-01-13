<nav
    class="bg-white shadow px-6 py-4 flex justify-between items-center
           sticky top-0 z-30"
>
    <!-- LEFT -->
    <div class="flex items-center gap-3">

        <!-- HAMBURGER (MOBILE ONLY) -->
        <button
            type="button"
            @click="$dispatch('toggle-sidebar')"
            class="w-10 h-10 flex items-center justify-center
                rounded-lg hover:bg-gray-100 transition lg:hidden"
            aria-label="Toggle Sidebar"
        >
            <i class="bi bi-list text-2xl text-green-700"></i>
        </button>

        <!-- TITLE -->
        <h1 class="text-xl font-semibold text-green-700">
            {{ $appName }}
        </h1>
    </div>

    <!-- RIGHT -->
    <div class="flex items-center gap-4">

        <!-- PROFILE DROPDOWN (LOCAL STATE) -->
        <div
            x-data="{ dropdown:false }"
            class="relative"
        >
            <!-- TRIGGER -->
            <button
                @click="dropdown = !dropdown"
                type="button"
                class="flex items-center gap-2 focus:outline-none"
            >
                <img
                    src="{{ Auth::user()->image
                        ? asset('storage/' . Auth::user()->image)
                        : asset('assets/img/admin.jpg') }}"
                    class="w-8 h-8 rounded-full object-cover"
                    alt="Profile"
                >

                <span class="hidden sm:block text-sm font-medium">
                    {{ Auth::user()->name }}
                </span>

                <i class="bi bi-caret-down-fill text-xs text-gray-500"></i>
            </button>

            <!-- DROPDOWN PANEL -->
            <div
                x-show="dropdown"
                x-transition.origin.top.right
                @click.outside="dropdown = false"
                x-cloak
                class="absolute right-0 mt-3 w-64 bg-white rounded-xl
                       shadow-xl z-50 overflow-hidden"
            >
                <!-- HEADER -->
                <div class="bg-green-600 text-white p-4 text-center">
                    <img
                        src="{{ Auth::user()->image
                            ? asset('storage/' . Auth::user()->image)
                            : asset('assets/img/admin.jpg') }}"
                        class="w-20 h-20 mx-auto rounded-full
                               border-4 border-white"
                        alt="Profile"
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
                <div class="p-3 border-t">
                    <a
                        href="{{ route('home') }}"
                        class="flex items-center px-3 py-2 text-sm
                               rounded-md hover:bg-gray-100 transition"
                    >
                        <i class="bi bi-house-door me-2"></i>
                        Kembali ke Home
                    </a>
                </div>

                <!-- FOOTER -->
                <div class="p-3 bg-gray-50 border-t">
                    <form
                        action="{{ route('logout') }}"
                        method="POST"
                    >
                        @csrf
                        <button
                            type="submit"
                            class="w-full flex justify-center items-center gap-2
                                   border border-gray-300 rounded-md py-2 text-sm
                                   hover:bg-red-500 hover:text-white transition"
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
