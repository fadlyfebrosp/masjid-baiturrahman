<aside class="relative bg-green-700 text-white flex flex-col h-full">

    <!-- CLOSE BUTTON (MOBILE ONLY) -->
    <button
        type="button"
        @click="$dispatch('close-sidebar')"
        class="absolute top-3 right-3 w-8 h-8
            flex items-center justify-center
            rounded-full hover:bg-green-600 lg:hidden"
    >
        <i class="bi bi-x-lg text-lg"></i>
    </button>

    <!-- PROFILE -->
    <div class="p-6 text-center border-b border-green-600">
        <img
            src="{{ asset('assets/img/admin.jpg') }}"
            class="w-16 h-16 mx-auto rounded-full mb-2"
            alt="Admin"
        >

        <h2 class="font-bold">
            {{ Auth::user()->name }}
        </h2>

        <p class="text-sm opacity-80 capitalize">
            {{ Auth::user()->role }}
        </p>
    </div>

    <!-- MENU -->
    <nav class="flex-1 mt-4 space-y-1 overflow-y-auto no-scrollbar">
        @php
            $menus = [
                ['route'=>'admin.dashboard','icon'=>'speedometer2','label'=>'Dashboard'],
                ['route'=>'admin.program.index','icon'=>'heart-fill','label'=>'Program Donasi'],
                ['route'=>'admin.beritadankegiatan.index','icon'=>'newspaper','label'=>'Berita & Kegiatan'],
                ['route'=>'admin.account','icon'=>'people','label'=>'Kelola Akun'],
                ['route'=>'admin.contactdonasioffline.index','icon'=>'telephone','label'=>'Contact Donasi Offline'],
                ['route'=>'admin.donasioffline.index','icon'=>'cash-stack','label'=>'Donasi Offline'],
                ['route'=>'admin.activitylog','icon'=>'clipboard-data','label'=>'Log Aktivitas'],
            ];
        @endphp

        @foreach($menus as $menu)
            <a
                href="{{ route($menu['route']) }}"
                class="flex items-center px-6 py-3 transition
                {{ request()->routeIs($menu['route'].'*')
                    ? 'bg-green-800'
                    : 'hover:bg-green-600'
                }}"
            >
                <i class="bi bi-{{ $menu['icon'] }} text-lg"></i>
                <span class="ml-3">{{ $menu['label'] }}</span>
            </a>
        @endforeach
    </nav>

    <!-- LOGOUT -->
    <form action="{{ route('admin.logout') }}" method="POST"
          class="border-t border-green-600">
        @csrf
        <button
            type="submit"
            class="w-full flex items-center px-6 py-3
                   hover:bg-green-600 transition"
        >
            <i class="bi bi-box-arrow-right"></i>
            <span class="ml-3">Logout</span>
        </button>
    </form>

</aside>
