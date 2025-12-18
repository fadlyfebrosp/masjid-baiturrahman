<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self' https://cdn.jsdelivr.net https://cdn.tailwindcss.com; img-src 'self' data:; script-src 'self' https://cdn.jsdelivr.net https://cdn.tailwindcss.com 'unsafe-inline'; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; frame-ancestors 'none';">
    <meta http-equiv="X-Frame-Options" content="DENY">
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="Referrer-Policy" content="no-referrer">
    <title>Login - Masjid Baiturrahman</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/icon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

  <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-8 relative overflow-hidden">
    <div class="absolute top-[-40px] right-[-60px] w-56 h-56 bg-gradient-to-tr from-green-400 to-green-200 rounded-[100px_50px_100px_50px] blur-xl opacity-60"></div>
    <div class="absolute bottom-[-50px] left-[-40px] w-56 h-56 bg-gradient-to-br from-green-100 to-green-400 rounded-[50px_100px_50px_100px] blur-xl opacity-60"></div>



    <div class="relative flex justify-center mb-4">
      <img src="{{ asset('assets/img/logo.png') }}" alt="Logo Masjid" class="w-40 h-40 object-contain">
    </div>

    <div class="relative text-center mb-6">
      <h2 class="text-2xl font-semibold text-gray-800">Login</h2>
      <p class="text-gray-500 text-sm">Silakan login jika sudah memiliki akun.</p>
    </div>

    <!-- FORM LOGIN -->
    <form action="{{ route('login.post') }}" method="POST" class="relative z-10" autocomplete="off" novalidate>
    @csrf

    <div class="relative mb-4">
        <input
        type="text"
        name="login"
        placeholder="Email atau No. Telepon"
        value="{{ old('login') }}"
        required
        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-600 focus:outline-none placeholder-gray-400">
        <i class="bi bi-person absolute right-4 top-3.5 text-gray-400"></i>
    </div>

    <div class="relative mb-6">
        <input
        type="password"
        id="loginPassword"
        name="password"
        placeholder="Password"
        required
        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-600 focus:outline-none placeholder-gray-400">
        <i class="bi bi-lock absolute right-10 top-3.5 text-gray-400"></i>
        <i id="toggleLoginPassword" class="bi bi-eye absolute right-4 top-3.5 text-gray-500 cursor-pointer"></i>
    </div>

    <button type="submit"
        class="w-full bg-green-700 text-white py-3 rounded-md font-medium hover:bg-green-800 transition-all shadow-md">
        Sign In
    </button>
    </form>
    <!-- END FORM -->

    <div class="relative text-center mt-6 text-sm">
      <p class="text-gray-600">Belum punya akun?
        <a href="{{ route('register') }}" class="text-green-700 font-medium hover:underline">Register disini</a>
      </p>
    </div>

    <div class="relative text-center mt-8 text-sm">
      <p class="text-gray-500">Lupa Password?</p>
      <a href="/forgot-password" class="text-blue-600 font-medium hover:underline">Rubah Password disini</a>
    </div>

    <div class="relative text-center mt-6 text-sm">
      <a href="/" class="inline-flex items-center text-gray-500 hover:text-green-700 transition-all">
        <i class="bi bi-arrow-left-circle me-2"></i> Kembali ke Beranda
      </a>
    </div>
  </div>

  <!-- Toggle Password -->
  <script>
    const toggleLoginPassword = document.querySelector("#toggleLoginPassword");
    const loginPassword = document.querySelector("#loginPassword");

    toggleLoginPassword.addEventListener("click", () => {
      const type = loginPassword.getAttribute("type") === "password" ? "text" : "password";
      loginPassword.setAttribute("type", type);
      toggleLoginPassword.classList.toggle("bi-eye");
      toggleLoginPassword.classList.toggle("bi-eye-slash");
    });
  </script>

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  @if ($errors->any())
  <script>
    let errorMessages = `{!! implode('<br>', $errors->all()) !!}`;
    Swal.fire({
      icon: 'error',
      title: 'Terjadi Kesalahan',
      html: errorMessages,
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
    setTimeout(() => {
      window.location.href = "{{ route('login') }}";
    }, 2000);
  </script>
  @endif

</body>
</html>
