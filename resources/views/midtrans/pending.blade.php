<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menunggu Pembayaran</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.11/dist/dotlottie-wc.js" type="module"></script>
</head>

<body class="bg-gray-100">

<div class="max-w-md mx-auto bg-white min-h-screen flex flex-col">

    <div class="flex items-center gap-3 p-4 border-b">
        <img alt="" class="w-20 h-14 object-contain" src="{{ $logo ?? asset('assets/img/Image-not-found.png') }}">
        <div class="flex items-center gap-2">
            <i class="bi bi-hourglass-split text-yellow-500 text-xl"></i>
            <h1 class="font-semibold text-gray-800">Pembayaran Menunggu</h1>
        </div>
    </div>

    <div class="flex-1 flex flex-col items-center justify-center p-6 text-center">

        <dotlottie-wc
            src="https://lottie.host/ed7638d2-9341-414d-9b27-472e9037990b/2tr15drnOz.lottie"
            autoplay
            loop
            style="width: 260px; height: 260px;">
        </dotlottie-wc>

        <h2 class="text-2xl font-bold text-yellow-500 mt-4 mb-2">
            Menunggu Pembayaran
        </h2>

        <p class="text-gray-600 max-w-xs">
            Silakan selesaikan pembayaran sesuai metode yang Anda pilih.
        </p>

        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-sm text-yellow-700">
            Invoice: <strong>{{ $transaction->reference }}</strong><br>
            Total: Rp {{ number_format($transaction->amount, 0, ',', '.') }}
        </div>

        <a href="/"
           class="mt-8 px-6 py-3 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg font-medium shadow">
            Kembali ke Beranda
        </a>
        <a href="{{ route('payment.back', $transaction->id) }}"
            class="mt-4 px-6 py-3 bg-green-500 hover:bg-green-600 text-white rounded-lg font-medium shadow">
            Kembali Ke Pembayaran
        </a>
    </div>

</div>

</body>
</html>
