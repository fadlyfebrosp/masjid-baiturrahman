<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title')</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="antialiased bg-gray-100 flex items-center justify-center min-h-screen">

  <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-8 relative overflow-hidden">
    <!-- Dekorasi -->
    <div class="absolute top-0 right-0 w-40 h-40 bg-green-300 rounded-bl-full"></div>
    <div class="absolute bottom-0 left-0 w-40 h-40 bg-green-100 rounded-tr-full"></div>

    <!-- Logo -->
    <div class="relative flex justify-center mb-4 z-10">
      <img src="{{ asset('assets/img/logo.png') }}" alt="Logo Masjid" class="w-32 h-32 object-contain">
    </div>

    <!-- Konten Error -->
    <div class="relative text-center z-10">
      <!-- Hilangkan tampilan kode angka -->
      <p class="text-lg text-gray-600 font-medium mb-6">@yield('message')</p>

      <!-- Ikon dinamis -->
      <div class="flex justify-center mb-6">
        @php $code = trim($__env->yieldContent('code')); @endphp
        @if ($code == '401')
            <i class="bi bi-person-x text-5xl text-red-600"></i>
        @elseif ($code == '402')
            <i class="bi bi-cash text-5xl text-yellow-600"></i>
        @elseif ($code == '403')
            <i class="bi bi-shield-lock text-5xl text-orange-600"></i>
        @elseif ($code == '404')
            <i class="bi bi-search text-5xl text-green-600"></i>
        @elseif ($code == '419')
            <i class="bi bi-hourglass-split text-5xl text-blue-600"></i>
        @elseif ($code == '429')
            <i class="bi bi-speedometer2 text-5xl text-purple-600"></i>
        @elseif ($code == '500')
            <i class="bi bi-bug text-5xl text-red-700"></i>
        @elseif ($code == '503')
            <i class="bi bi-tools text-5xl text-gray-700"></i>
        @else
            <i class="bi bi-exclamation-circle text-5xl text-gray-500"></i>
        @endif
      </div>

      <!-- Tombol aksi -->
      <div class="space-y-3">
        @if ($code == '401')
          <a href="/login" class="inline-block w-full bg-green-700 text-white py-3 rounded-md font-medium hover:bg-green-800 transition-all shadow-md">
            Masuk Kembali
          </a>
          <a href="javascript:history.back()" class="inline-block w-full text-gray-600 font-medium hover:underline">
            Kembali ke Halaman Sebelumnya
          </a>

        @elseif ($code == '403')
          <a href="/" class="inline-block w-full bg-green-700 text-white py-3 rounded-md font-medium hover:bg-green-800 transition-all shadow-md">
            Kembali ke Beranda
          </a>

        @elseif ($code == '404')
          <a href="/" class="inline-block w-full bg-green-700 text-white py-3 rounded-md font-medium hover:bg-green-800 transition-all shadow-md">
            Kembali ke Beranda
          </a>
          <a href="javascript:history.back()" class="inline-block w-full text-gray-600 font-medium hover:underline">
            Halaman Sebelumnya
          </a>

        @elseif ($code == '419')
          <a href="javascript:location.reload()" class="inline-block w-full bg-blue-700 text-white py-3 rounded-md font-medium hover:bg-blue-800 transition-all shadow-md">
            Muat Ulang Halaman
          </a>

        @elseif ($code == '429')
          <a href="javascript:location.reload()" class="inline-block w-full bg-purple-700 text-white py-3 rounded-md font-medium hover:bg-purple-800 transition-all shadow-md">
            Coba Lagi
          </a>

        @elseif ($code == '500')
          <a href="/" class="inline-block w-full bg-red-700 text-white py-3 rounded-md font-medium hover:bg-red-800 transition-all shadow-md">
            Kembali ke Beranda
          </a>

        @elseif ($code == '503')
          <a href="javascript:history.back()" class="inline-block w-full bg-gray-600 text-white py-3 rounded-md font-medium hover:bg-gray-700 transition-all shadow-md">
            Kembali ke Halaman Sebelumnya
          </a>

        @else
          <a href="/" class="inline-block w-full bg-green-700 text-white py-3 rounded-md font-medium hover:bg-green-800 transition-all shadow-md">
            Kembali ke Beranda
          </a>
        @endif
      </div>
    </div>
  </div>

</body>
</html>
