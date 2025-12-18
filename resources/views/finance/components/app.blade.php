<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title')</title>
  <link rel="icon" type="image/png" href="{{ asset('assets/img/icon.png') }}">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    .no-scrollbar::-webkit-scrollbar {
      display: none;
    }
    .no-scrollbar {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }
  </style>
</head>

<body class="bg-gray-50 text-gray-800 antialiased">
  <div class="flex h-screen overflow-hidden">

    <!-- Sidebar -->
    @include('finance.components.sidebar')

    <!-- Konten utama -->
    <div class="flex-1 flex flex-col">

      <!-- Navbar -->
      @include('finance.components.navbar')

      <!-- Isi Halaman -->
      <main class="flex-1 p-8 overflow-y-auto no-scrollbar">
        @yield('content')
      </main>

      <!-- Footer -->
      @include('finance.components.footer')

    </div>
  </div>
</body>
</html>
