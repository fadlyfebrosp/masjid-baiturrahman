<nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
    <!-- TITLE -->
    <h1 class="text-xl font-semibold text-green-700">Dashboard</h1>

    <!-- RIGHT MENU -->
    <div class="flex items-center space-x-4">

        <!-- NOTIFICATION -->
        <button class="relative">
            <i class="bi bi-bell text-xl text-gray-600"></i>
            <span
                class="absolute -top-1 -right-1 bg-red-500 text-white text-xs px-1 rounded-full">
                3
            </span>
        </button>

        <!-- PROFILE DROPDOWN -->
        <div x-data="{ open: false }" class="relative">
            <button
                @click="open = !open"
                class="flex items-center space-x-2 focus:outline-none"
            >
                <img
                    src="{{ asset('assets/img/admin.jpg') }}"
                    class="w-8 h-8 rounded-full object-cover"
                    alt="Profile"
                >
                <span class="text-sm font-medium text-gray-700">
                    Administrator
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
                        src="{{ asset('assets/img/admin.jpg') }}"
                        class="w-24 h-24 rounded-full mx-auto border-4 border-white object-cover"
                        alt=""
                    >
                    <h3 class="mt-3 text-lg font-semibold flex justify-center items-center gap-1">
                        Administrator
                        <i class="bi bi-patch-check-fill text-white text-sm"></i>
                    </h3>

                    <p class="text-sm mt-2">
                        Tanggal Bergabung : 04-05-2021
                    </p>
                    <p class="text-sm">
                        Terakhir Login : 18-12-2025 ( 23:35:56 )
                    </p>
                </div>

                <!-- FOOTER -->
                <div class="p-4 bg-gray-50">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button
                            type="submit"
                            class="w-full border border-gray-300 rounded-md py-2 text-gray-700 hover:bg-red-500 hover:text-white transition"
                        >
                            Keluar
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</nav>
