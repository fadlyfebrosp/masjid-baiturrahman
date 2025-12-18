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
  <style>
    .mobile-frame {
        width: 100%;
        max-width: 430px;
        margin: 0 auto;
        background: white;
        min-height: 100vh;
    }
    .prose ul {
        list-style-type: disc !important;
        margin-left: 1.2rem !important;
    }
    .prose ol {
        list-style-type: decimal !important;
        margin-left: 1.2rem !important;
    }
    .prose ul li::marker {
        color: rgb(22 163 74);
        font-size: 1.2em;
    }
    .prose img {
        margin: 12px 0;
        border-radius: 8px;
        width: 100%;
        height: auto;
    }
    .prose strong {
        color: #111;
    }
    .no-scrollbar::-webkit-scrollbar {
      display: none;
    }
    .no-scrollbar {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }
    .input {
        @apply w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 shadow-sm
               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500;
    }
  </style>
</head>

<body class="bg-gray-50 text-gray-800 antialiased">
  <div class="flex h-screen overflow-hidden">

    <!-- Sidebar -->
    @include('admin.components.sidebar')

    <!-- Konten utama -->
    <div class="flex-1 flex flex-col">

      <!-- Navbar -->
      @include('admin.components.navbar')

      <!-- Isi Halaman -->
      <main class="flex-1 p-8 overflow-y-auto no-scrollbar">
        @yield('content')
      </main>

      <!-- Footer -->
      @include('admin.components.footer')
      <!-- Flowbite core + Datepicker plugin -->
        <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>

        <!-- Preline -->
        <script src="https://unpkg.com/preline/dist/preline.js"></script>

        <script>
        document.addEventListener("DOMContentLoaded", () => {
            if (window.HSStaticMethods?.autoInit) {
                window.HSStaticMethods.autoInit();
            }
        });

        // Pastikan Alpine re-render tidak mematikan datepicker
        document.addEventListener('alpine:init', () => {
            window.HSStaticMethods?.autoInit();
        });
        </script>
    </div>
  </div>
</body>
</html>
