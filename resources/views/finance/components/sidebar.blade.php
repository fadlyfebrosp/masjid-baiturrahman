<div x-data="{ open: true }" class="relative flex min-h-screen">

  <!-- ================= SIDEBAR ================= -->
  <div
    :class="open ? 'w-64' : 'w-20'"
    class="bg-green-700 text-white flex flex-col transition-all duration-300 overflow-hidden"
  >

    <!-- ================= HEADER ================= -->
    <div class="p-6 text-center border-b border-green-600">
      <template x-if="open">
        <div>
          <img alt=""
            src="{{ Auth::user()->image
                ? asset('storage/' . Auth::user()->image)
                : asset('assets/img/admin.jpg') }}"
            class="w-16 h-16 mx-auto rounded-full mb-2 object-cover"
          >
          <h2 class="font-bold text-lg">{{ Auth::user()->name }}</h2>
          <p class="text-sm opacity-80 capitalize">{{ Auth::user()->role }}</p>
        </div>
      </template>

      <template x-if="!open">
        <img alt=""
          src="{{ Auth::user()->image
              ? asset('storage/' . Auth::user()->image)
              : asset('assets/img/admin.jpg') }}"
          class="w-10 h-10 mx-auto rounded-full object-cover"
        >
      </template>
    </div>

    <!-- ================= NAVIGATION ================= -->
    <nav class="flex-1 mt-6 overflow-y-auto">

      <!-- DASHBOARD -->
      <a href="{{ route('finance.dashboard') }}"
        class="flex items-center px-6 py-3 transition
        {{ request()->routeIs('finance.dashboard') ? 'bg-green-800' : 'hover:bg-green-600' }}">
        <i class="bi bi-speedometer2"></i>
        <span x-show="open" class="ml-3">Dashboard</span>
      </a>

      <!-- TRANSACTION -->
      <a href="{{ route('finance.transaction') }}"
        class="flex items-center px-6 py-3 transition
        {{ request()->routeIs('finance.transaction') ? 'bg-green-800' : 'hover:bg-green-600' }}">
        <i class="bi bi-cash-stack"></i>
        <span x-show="open" class="ml-3">Transaction</span>
      </a>

      <!-- ================= MASTER DATA ================= -->
      <div
        x-data="{
          openMaster: {{
            request()->routeIs('finance.pemasukkan.index')
            || request()->routeIs('finance.pengeluaran.index')
            ? 'true' : 'false'
          }}
        }"
        class="space-y-1"
      >
        <button
          @click="openMaster = !openMaster"
          class="w-full flex items-center px-6 py-3 transition
          {{
            request()->routeIs('finance.pemasukkan.index')
            || request()->routeIs('finance.pengeluaran.index')
              ? 'bg-green-800'
              : 'hover:bg-green-600'
          }}"
        >
          <i class="bi bi-database"></i>
          <span x-show="open" class="ml-3 flex-1 text-left">Master Data</span>
          <i x-show="open" :class="openMaster ? 'bi-chevron-down rotate-180' : 'bi-chevron-down'"
             class="bi transition-transform"></i>
        </button>

        <div x-show="openMaster && open" x-transition class="ml-12 space-y-1 text-sm">
          <a href="{{ route('finance.pemasukkan.index') }}"
             class="block px-4 py-2 rounded-md
             {{ request()->routeIs('finance.pemasukkan.index') ? 'bg-green-600' : 'hover:bg-green-500' }}">
            Pemasukkan
          </a>

          <a href="{{ route('finance.pengeluaran.index') }}"
             class="block px-4 py-2 rounded-md
             {{ request()->routeIs('finance.pengeluaran.index') ? 'bg-green-600' : 'hover:bg-green-500' }}">
            Pengeluaran
          </a>
        </div>
      </div>

      <!-- ================= LAPORAN KEUANGAN ================= -->
      <div
        x-data="{
          openReport: {{
            request()->routeIs('finance.pemasukkan.laporan')
            || request()->routeIs('finance.pengeluaran.laporan')
            ? 'true' : 'false'
          }}
        }"
        class="space-y-1 mt-2"
      >
        <button
          @click="openReport = !openReport"
          class="w-full flex items-center px-6 py-3 transition
          {{
            request()->routeIs('finance.*.laporan')
              ? 'bg-green-800'
              : 'hover:bg-green-600'
          }}"
        >
          <i class="bi bi-bar-chart-line"></i>
          <span x-show="open" class="ml-3 flex-1 text-left">Laporan Keuangan</span>
          <i x-show="open" :class="openReport ? 'bi-chevron-down rotate-180' : 'bi-chevron-down'"
             class="bi transition-transform"></i>
        </button>

        <div x-show="openReport && open" x-transition class="ml-12 space-y-1 text-sm">
          <a href="{{ route('finance.pemasukkan.laporan') }}"
             class="block px-4 py-2 rounded-md
             {{ request()->routeIs('finance.pemasukkan.laporan') ? 'bg-green-600' : 'hover:bg-green-500' }}">
            Pemasukkan
          </a>

          <a href="{{ route('finance.pengeluaran.laporan') }}"
             class="block px-4 py-2 rounded-md
             {{ request()->routeIs('finance.pengeluaran.laporan') ? 'bg-green-600' : 'hover:bg-green-500' }}">
            Pengeluaran
          </a>
        </div>
      </div>

    </nav>

    <!-- ================= TOGGLE ================= -->
    <div class="flex justify-center my-4">
      <button @click="open = !open"
        class="w-10 h-10 rounded-full bg-green-600 hover:bg-green-500 flex items-center justify-center">
        <i :class="open ? 'bi-chevron-left' : 'bi-chevron-right'" class="bi"></i>
      </button>
    </div>

    <!-- ================= LOGOUT ================= -->
    <form action="{{ route('logout') }}" method="POST" class="border-t border-green-600">
      @csrf
      <button class="w-full flex items-center px-6 py-3 hover:bg-green-600">
        <i class="bi bi-box-arrow-right"></i>
        <span x-show="open" class="ml-3">Logout</span>
      </button>
    </form>

  </div>
</div>
