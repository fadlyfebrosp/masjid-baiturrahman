<nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
    <h1 class="text-xl font-semibold text-green-700">
        {{ $appName }}
    </h1>

    <div class="flex items-center space-x-4">

        <!-- 👤 USER MENU -->
        <div x-data="{ open: false }" class="relative">
            <button
                @click="open = !open"
                class="flex items-center space-x-2 focus:outline-none"
            >
                <img
                    src="{{ asset('assets/img/admin.jpg') }}"
                    class="w-8 h-8 rounded-full"
                    alt="Admin"
                >
                <i class="bi bi-caret-down-fill text-gray-500 text-xs"></i>
            </button>

            <div
                x-show="open"
                x-transition
                @click.outside="open = false"
                class="absolute right-0 mt-2 w-48 bg-white border rounded-lg shadow-lg z-50"
            >
                <a
                    href="{{ route('home') }}"
                    class="block px-4 py-2 text-sm hover:bg-gray-100"
                >
                    <i class="bi bi-house-door me-2"></i>
                    Kembali ke Home
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="w-full text-left px-4 py-2 text-red-500 hover:bg-gray-100"
                    >
                        <i class="bi bi-box-arrow-right me-2"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>

    </div>
</nav>
