<nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
  <h1 class="text-xl font-semibold text-green-700">Dashboard</h1>

  <div class="flex items-center space-x-4">
    <button class="relative">
      <i class="bi bi-bell text-xl text-gray-600"></i>
      <span class="absolute top-0 right-0 bg-red-500 text-white text-xs px-1 rounded-full">3</span>
    </button>

    <div x-data="{ open: false }" class="relative">
      <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
        <img src="{{ asset('assets/img/admin.jpg') }}" class="w-8 h-8 rounded-full" alt="">
        <i class="bi bi-caret-down-fill text-gray-500 text-xs"></i>
      </button>

      <div x-show="open" @click.outside="open = false" class="absolute right-0 mt-2 w-48 bg-white border rounded-lg shadow-lg">
        <a href="#" class="block px-4 py-2 hover:bg-gray-100"><i class="bi bi-person me-2"></i> Profil</a>
        <a href="#" class="block px-4 py-2 hover:bg-gray-100"><i class="bi bi-gear me-2"></i> Pengaturan</a>
        <a href="#" class="block px-4 py-2 text-red-500 hover:bg-gray-100"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
      </div>
    </div>
  </div>
</nav>
