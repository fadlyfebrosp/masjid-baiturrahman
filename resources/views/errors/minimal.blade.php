<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>

    <!-- Icons & Tailwind -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Animations -->
    <style>
        @keyframes shake {
            0%,100% { transform: translateX(0); }
            25% { transform: translateX(-6px); }
            75% { transform: translateX(6px); }
        }
        @keyframes bounce-soft {
            0%,100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        @keyframes pulse-slow {
            0%,100% { opacity: 1; }
            50% { opacity: .5; }
        }
        @keyframes spin-slow {
            from { transform: rotate(0); }
            to { transform: rotate(360deg); }
        }
        @keyframes zoom-in {
            from { transform: scale(.7); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .animate-shake { animation: shake .6s ease-in-out; }
        .animate-bounce-soft { animation: bounce-soft 1.6s infinite; }
        .animate-pulse-slow { animation: pulse-slow 2s infinite; }
        .animate-spin-slow { animation: spin-slow 3s linear infinite; }
        .animate-zoom { animation: zoom-in .5s ease-out; }
    </style>
</head>

<body class="antialiased bg-gray-100 flex items-center justify-center min-h-screen">

@php
    $code = trim($__env->yieldContent('code'));

    $iconClass = match ($code) {
        '401' => 'bi-person-x text-red-600 animate-shake',
        '402' => 'bi-cash text-yellow-600 animate-bounce-soft',
        '403' => 'bi-shield-lock text-orange-600 animate-pulse-slow',
        '404' => 'bi-search text-green-600 animate-bounce-soft',
        '419' => 'bi-hourglass-split text-blue-600 animate-spin-slow',
        '429' => 'bi-speedometer2 text-purple-600 animate-pulse-slow',
        '500' => 'bi-bug text-red-700 animate-shake',
        '503' => 'bi-tools text-gray-700 animate-spin-slow',
        default => 'bi-exclamation-circle text-gray-500 animate-pulse-slow',
    };

    $buttonColor = match ($code) {
        '401','403','404' => 'bg-green-700 hover:bg-green-800',
        '419' => 'bg-blue-700 hover:bg-blue-800',
        '429' => 'bg-purple-700 hover:bg-purple-800',
        '500' => 'bg-red-700 hover:bg-red-800',
        '503' => 'bg-gray-600 hover:bg-gray-700',
        default => 'bg-green-700 hover:bg-green-800',
    };
@endphp

<div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-8 relative overflow-hidden">

    <!-- Decorative shapes -->
    <div class="absolute top-0 right-0 w-40 h-40 bg-green-300 rounded-bl-full"></div>
    <div class="absolute bottom-0 left-0 w-40 h-40 bg-green-100 rounded-tr-full"></div>

    <!-- Logo -->
    <div class="relative flex justify-center mb-4 z-10 animate-zoom">
        <img src="{{ $logo }}" alt="Logo Masjid" class="w-40 h-40 object-contain">
    </div>

    <!-- Content -->
    <div class="relative text-center z-10">

        <p class="text-lg text-gray-600 font-medium mb-6">
            @yield('message')
        </p>

        <!-- Icon -->
        <div class="flex justify-center mb-6">
            <i class="bi {{ $iconClass }} text-8xl"></i>
        </div>

        <!-- Actions -->
        <div class="space-y-3">
            @if ($code == '401')
                <a href="/login"
                   class="inline-block w-full {{ $buttonColor }} text-white py-3 rounded-md font-medium transition-all shadow-md hover:scale-105">
                    Masuk Kembali
                </a>
                <a href="javascript:history.back()" class="inline-block w-full text-gray-600 hover:underline">
                    Halaman Sebelumnya
                </a>

            @elseif ($code == '404' || $code == '403' || $code == '500')
                <a href="/"
                   class="inline-block w-full {{ $buttonColor }} text-white py-3 rounded-md font-medium transition-all shadow-md hover:scale-105">
                    Kembali ke Beranda
                </a>

            @elseif ($code == '419' || $code == '429')
                <a href="javascript:location.reload()"
                   class="inline-block w-full {{ $buttonColor }} text-white py-3 rounded-md font-medium transition-all shadow-md hover:scale-105">
                    Muat Ulang Halaman
                </a>

            @elseif ($code == '503')
                <a href="javascript:history.back()"
                   class="inline-block w-full {{ $buttonColor }} text-white py-3 rounded-md font-medium transition-all shadow-md hover:scale-105">
                    Kembali
                </a>

            @else
                <a href="/"
                   class="inline-block w-full {{ $buttonColor }} text-white py-3 rounded-md font-medium transition-all shadow-md hover:scale-105">
                    Kembali ke Beranda
                </a>
            @endif
        </div>
    </div>
</div>

</body>
</html>
