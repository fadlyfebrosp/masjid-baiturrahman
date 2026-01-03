<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Gagal</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.11/dist/dotlottie-wc.js" type="module"></script>
</head>

<body class="bg-gray-100">

<div class="max-w-md mx-auto bg-white min-h-screen flex flex-col">

    <!-- HEADER -->
    <div class="flex items-center gap-3 p-4 border-b">
        <img src="{{ $logo }}"
             class="w-20 h-14 object-contain"
             alt="Logo">

        <div class="flex items-center gap-2">
            <i class="bi bi-x-circle text-red-600 text-xl"></i>
            <h1 class="font-semibold text-gray-800">Pembayaran Gagal</h1>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="flex-1 flex flex-col items-center justify-center p-6 text-center">

        <!-- LOTTIE FAILED -->
        <dotlottie-wc
            src="https://lottie.host/ea0b676a-6210-41a5-b0aa-e8ec1a823bf2/7uhAtEDWnI.lottie"
            autoplay
            loop
            style="width: 260px; height: 260px;">
        </dotlottie-wc>

        <h2 class="text-2xl font-bold text-red-600 mt-4 mb-2">
            Transaksi Gagal
        </h2>

        <p class="text-gray-600 max-w-xs">
            Pembayaran tidak berhasil diselesaikan atau dibatalkan.
            Silakan coba kembali dengan metode pembayaran lain.
        </p>

        <!-- INFO TRANSAKSI -->
        <div class="mt-6 bg-red-50 border border-red-200 rounded-lg p-4 text-sm text-red-700 text-left w-full">
            <div class="flex justify-between">
                <span>Invoice</span>
                <span class="font-medium">{{ $transaction->reference }}</span>
            </div>

            <div class="flex justify-between">
                <span>Status</span>
                <span class="font-medium uppercase">{{ $transaction->status }}</span>
            </div>

            <div class="flex justify-between">
                <span>Total</span>
                <span class="font-medium">
                    Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-3 text-sm text-yellow-700">
            Jika saldo sudah terpotong, mohon hubungi admin atau pihak pembayaran.
        </div>

        <a href="/"
           class="mt-8 px-6 py-3 bg-red-600 hover:bg-red-700 transition text-white rounded-lg font-medium shadow">
            Kembali ke Beranda
        </a>
    </div>

</div>

</body>
</html>
