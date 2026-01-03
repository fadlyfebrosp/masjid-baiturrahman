<div x-data="{ open: true }" class="relative flex min-h-screen">

    <aside
        :class="open ? 'w-64' : 'w-20'"
        class="bg-green-700 text-white flex flex-col h-screen
               transition-all duration-300 overflow-hidden"
    >

        <!-- ================= PROFILE ================= -->
        <div class="p-6 text-center border-b border-green-600 shrink-0">
            <template x-if="open">
                <div>
                    <img
                        src="{{ asset('assets/img/admin.jpg') }}"
                        class="w-16 h-16 mx-auto rounded-full mb-2"
                        alt=""
                    >
                    <h2 class="font-bold text-lg">{{ Auth::user()->name }}</h2>
                    <p class="text-sm opacity-80 capitalize">
                        {{ Auth::user()->role }}
                    </p>
                </div>
            </template>

            <template x-if="!open">
                <img
                    src="{{ asset('assets/img/admin.jpg') }}"
                    class="w-10 h-10 mx-auto rounded-full"
                    alt=""
                >
            </template>
        </div>

        <!-- ================= MENU (SCROLLABLE) ================= -->
        <nav
            :class="open ? 'overflow-y-auto' : 'overflow-hidden'"
            class="flex-1 mt-4 space-y-1
                   scrollbar-thin scrollbar-thumb-green-500 scrollbar-track-green-700"
        >

            <!-- DASHBOARD -->
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center px-6 py-3 transition
               {{ request()->routeIs('admin.dashboard') ? 'bg-green-800' : 'hover:bg-green-600' }}">
                <i class="bi bi-speedometer2 text-lg"></i>
                <span x-show="open" class="ml-3">Dashboard</span>
            </a>

            <!-- PROGRAM DONASI -->
            <a href="{{ route('admin.program.index') }}"
               class="flex items-center px-6 py-3 transition
               {{ request()->routeIs('admin.program.*') ? 'bg-green-800' : 'hover:bg-green-600' }}">
                <i class="bi bi-heart-fill text-lg"></i>
                <span x-show="open" class="ml-3">Program Donasi</span>
            </a>

            <!-- BERITA -->
            <a href="{{ route('admin.beritadankegiatan.index') }}"
               class="flex items-center px-6 py-3 transition
               {{ request()->routeIs('admin.beritadankegiatan.*') ? 'bg-green-800' : 'hover:bg-green-600' }}">
                <i class="bi bi-newspaper text-lg"></i>
                <span x-show="open" class="ml-3">Berita & Kegiatan</span>
            </a>

            <!-- AKUN -->
            <a href="{{ route('admin.account') }}"
               class="flex items-center px-6 py-3 transition
               {{ request()->routeIs('admin.account*') ? 'bg-green-800' : 'hover:bg-green-600' }}">
                <i class="bi bi-people text-lg"></i>
                <span x-show="open" class="ml-3">Kelola Akun</span>
            </a>

            <!-- CONTACT -->
            <a href="{{ route('admin.contactdonasioffline.index') }}"
               class="flex items-center px-6 py-3 transition
               {{ request()->routeIs('admin.contactdonasioffline.*') ? 'bg-green-800' : 'hover:bg-green-600' }}">
                <i class="bi bi-telephone text-lg"></i>
                <span x-show="open" class="ml-3">Contact Donasi Offline</span>
            </a>

            <!-- DONASI OFFLINE -->
            <a href="{{ route('admin.donasioffline.index') }}"
               class="flex items-center px-6 py-3 transition
               {{ request()->routeIs('admin.donasioffline.*') ? 'bg-green-800' : 'hover:bg-green-600' }}">
                <i class="bi bi-cash-stack text-lg"></i>
                <span x-show="open" class="ml-3">Donasi Offline</span>
            </a>

            <!-- ACTIVITY LOG -->
            <a href="{{ route('admin.activitylog') }}"
               class="flex items-center px-6 py-3 transition
               {{ request()->routeIs('admin.activitylog') ? 'bg-green-800' : 'hover:bg-green-600' }}">
                <i class="bi bi-clipboard-data text-lg"></i>
                <span x-show="open" class="ml-3">Log Aktivitas</span>
            </a>

            <!-- ================= SETTINGS ================= -->
            <div
                x-data="{ openSetting: {{ request()->routeIs('admin.pengaturan.*') ? 'true' : 'false' }} }"
                class="space-y-1"
            >
                <button
                    @click="openSetting = !openSetting"
                    class="w-full flex items-center px-6 py-3 transition
                    {{ request()->routeIs('admin.pengaturan.*') ? 'bg-green-800' : 'hover:bg-green-600' }}"
                >
                    <i class="bi bi-gear text-lg"></i>

                    <span x-show="open" class="ml-3 flex-1 text-left">
                        Pengaturan
                    </span>

                    <i
                        x-show="open"
                        :class="openSetting ? 'rotate-180' : ''"
                        class="bi bi-chevron-down transition-transform"
                    ></i>
                </button>

                <div
                    x-show="openSetting && open"
                    x-transition
                    class="ml-12 space-y-1 text-sm"
                >
                    <a href="{{ route('admin.pengaturan.general') }}"
                       class="block px-4 py-2 rounded-md transition
                       {{ request()->routeIs('admin.pengaturan.general*') ? 'bg-green-600' : 'hover:bg-green-500' }}">
                        General
                    </a>

                    <a href="{{ route('admin.pengaturan.security') }}"
                       class="block px-4 py-2 rounded-md transition
                       {{ request()->routeIs('admin.pengaturan.security*') ? 'bg-green-600' : 'hover:bg-green-500' }}">
                        Security
                    </a>

                    <a href="{{ route('admin.pengaturan.midtrans') }}"
                       class="block px-4 py-2 rounded-md transition
                       {{ request()->routeIs('admin.pengaturan.midtrans*') ? 'bg-green-600' : 'hover:bg-green-500' }}">
                        Midtrans
                    </a>
                </div>
            </div>

        </nav>

        <!-- ================= TOGGLE ================= -->
        <div class="flex justify-center my-4 shrink-0">
            <button
                @click="open = !open"
                class="w-10 h-10 rounded-full bg-green-600 hover:bg-green-500
                       transition flex items-center justify-center"
            >
                <i
                    :class="open ? 'bi-chevron-left' : 'bi-chevron-right'"
                    class="bi text-white text-lg"
                ></i>
            </button>
        </div>

        <!-- ================= LOGOUT ================= -->
        <form
            action="{{ route('admin.logout') }}"
            method="POST"
            class="border-t border-green-600 shrink-0"
        >
            @csrf
            <button
                type="submit"
                class="w-full flex items-center px-6 py-3 hover:bg-green-600 transition"
            >
                <i class="bi bi-box-arrow-right text-lg"></i>
                <span x-show="open" class="ml-3">Logout</span>
            </button>
        </form>

    </aside>
</div>
