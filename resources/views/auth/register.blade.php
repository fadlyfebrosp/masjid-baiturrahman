<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self' https://cdn.jsdelivr.net https://cdn.tailwindcss.com; img-src 'self' data:; script-src 'self' https://cdn.jsdelivr.net https://cdn.tailwindcss.com 'unsafe-inline'; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; frame-ancestors 'none';">
    <meta http-equiv="X-Frame-Options" content="DENY">
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="Referrer-Policy" content="no-referrer">
    <title>Daftar Akun - Masjid Baiturrahman</title>
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

    <div class="relative text-center mb-6 z-10">
      <h2 class="text-2xl font-semibold text-gray-800">Daftar Akun Baru</h2>
      <p class="text-gray-500 text-sm">Silakan isi data berikut untuk membuat akun Anda.</p>
    </div>

    {{-- ✅ Notifikasi sukses --}}
    @if (session('success'))
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <script>
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: '{{ session('success') }}',
          showConfirmButton: false,
          timer: 2000
        }).then(() => {
          window.location.href = "{{ route('login') }}";
        });
      </script>
    @endif

    {{-- ❌ Notifikasi error --}}
    @if ($errors->any())
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    <form action="{{ route('register.post') }}" method="POST" class="relative z-10" autocomplete="off" novalidate>
      @csrf

      {{-- Nama --}}
      <div class="relative mb-4">
        <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama Lengkap"
          class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-600 focus:outline-none placeholder-gray-400">
        <i class="bi bi-person absolute right-4 top-3.5 text-gray-400"></i>
      </div>

      {{-- Email --}}
      <div class="relative mb-4">
        <input type="email" name="email" value="{{ old('email') }}" placeholder="Alamat Email"
          class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-600 focus:outline-none placeholder-gray-400">
        <i class="bi bi-envelope absolute right-4 top-3.5 text-gray-400"></i>
      </div>

      {{-- No Telepon --}}
      <div class="relative mb-4">
        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Nomor Telepon"
          class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-600 focus:outline-none placeholder-gray-400">
        <i class="bi bi-telephone absolute right-4 top-3.5 text-gray-400"></i>
      </div>

      {{-- Jenis Kelamin --}}
      <div class="relative mb-4">
        <select name="gender"
          class="w-full px-4 py-3 border border-gray-300 rounded-md text-gray-600 focus:ring-2 focus:ring-green-600 focus:outline-none">
          <option value="" disabled selected>-- Pilih Jenis Kelamin --</option>
          <option value="Laki-laki" {{ old('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
          <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
        </select>
        <i class="bi bi-gender-ambiguous absolute right-4 top-3.5 text-gray-400"></i>
      </div>

      {{-- Password --}}
      <div class="relative mb-4">
        <input type="password" id="password" name="password" placeholder="Kata Sandi"
          class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-600 focus:outline-none placeholder-gray-400">
        <i class="bi bi-lock absolute right-10 top-3.5 text-gray-400"></i>
        <i id="togglePassword" class="bi bi-eye absolute right-4 top-3.5 text-gray-500 cursor-pointer"></i>
      </div>

      {{-- Konfirmasi Password --}}
      <div class="relative mb-6">
        <input type="password" id="password2" name="password_confirmation" placeholder="Ulangi Kata Sandi"
          class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-600 focus:outline-none placeholder-gray-400">
        <i class="bi bi-lock-fill absolute right-10 top-3.5 text-gray-400"></i>
      </div>

      {{-- Tombol Submit --}}
      <button type="submit"
        class="w-full bg-green-700 text-white py-3 rounded-md font-medium hover:bg-green-800 transition-all shadow-md">
        Daftar Sekarang
      </button>
    </form>

    <div class="relative text-center mt-6 text-sm z-10">
      <p class="text-gray-600">Sudah punya akun?
        <a href="{{ route('login') }}" class="text-green-700 font-medium hover:underline">Masuk di sini</a>
      </p>
    </div>

    <div class="relative text-center mt-6 text-sm z-10">
      <a href="/" class="inline-flex items-center text-gray-500 hover:text-green-700 transition-all">
        <i class="bi bi-arrow-left-circle me-2"></i> Kembali ke Beranda
      </a>
    </div>
  </div>

  {{-- JavaScript --}}
  <script>
    // Toggle tampil/sembunyi password
    const togglePassword = document.querySelector("#togglePassword");
    const password = document.querySelector("#password");
    const password2 = document.querySelector("#password2");

    togglePassword.addEventListener("click", () => {
      const type = password.getAttribute("type") === "password" ? "text" : "password";
      password.setAttribute("type", type);
      password2.setAttribute("type", type);
      togglePassword.classList.toggle("bi-eye");
      togglePassword.classList.toggle("bi-eye-slash");
    });

    // Isi otomatis ulangi password (optional)
    password.addEventListener("input", () => {
      password2.value = password.value;
    });
  </script>

</body>
</html>
