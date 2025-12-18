<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password - Masjid Baiturrahman</title>
  <link rel="icon" type="image/png" href="{{ asset('assets/img/icon.png') }}">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

  <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-8 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-40 h-40 bg-green-300 rounded-bl-full"></div>
    <div class="absolute bottom-0 left-0 w-40 h-40 bg-green-100 rounded-tr-full"></div>

    <div class="relative flex justify-center mb-4">
      <img src="{{ asset('assets/img/logo.png') }}" alt="Logo Masjid" class="w-40 h-40 object-contain">
    </div>

    <div class="relative text-center mb-6">
      <h2 class="text-2xl font-semibold text-gray-800">Reset Password</h2>
      <p class="text-gray-500 text-sm">Masukkan password baru Anda.</p>
    </div>

    <form method="POST" action="{{ route('password.update') }}" class="relative z-10">
      @csrf
      <input type="hidden" name="token" value="{{ $token }}">

      <div class="relative mb-4">
        <input
          type="email"
          name="email"
          placeholder="Email"
          required
          class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-600 focus:outline-none placeholder-gray-400">
        <i class="bi bi-envelope absolute right-4 top-3.5 text-gray-400"></i>
      </div>

      <div class="relative mb-4">
        <input
          type="password"
          name="password"
          placeholder="Password Baru"
          required
          class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-600 focus:outline-none placeholder-gray-400">
        <i class="bi bi-lock absolute right-4 top-3.5 text-gray-400"></i>
      </div>

      <div class="relative mb-6">
        <input
          type="password"
          name="password_confirmation"
          placeholder="Konfirmasi Password"
          required
          class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-600 focus:outline-none placeholder-gray-400">
        <i class="bi bi-lock-fill absolute right-4 top-3.5 text-gray-400"></i>
      </div>

      <button type="submit"
        class="w-full bg-green-700 text-white py-3 rounded-md font-medium hover:bg-green-800 transition-all shadow-md">
        Simpan Password
      </button>
    </form>

    <div class="relative text-center mt-6 text-sm">
      <a href="{{ route('login') }}" class="text-blue-600 font-medium hover:underline">
        Kembali ke Login
      </a>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  @if ($errors->any())
  <script>
    Swal.fire({
      icon: 'error',
      title: 'Terjadi Kesalahan',
      html: `{!! implode('<br>', $errors->all()) !!}`,
      confirmButtonText: 'OK'
    });
  </script>
  @endif

  @if (session('success'))
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Berhasil',
      text: '{{ session("success") }}',
      showConfirmButton: false,
      timer: 2000
    });
  </script>
  @endif

</body>
</html>
