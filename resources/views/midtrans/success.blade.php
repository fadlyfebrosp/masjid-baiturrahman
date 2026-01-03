<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.11/dist/dotlottie-wc.js" type="module"></script>
</head>

<body class="bg-gray-100">

<div class="max-w-md mx-auto bg-white min-h-screen flex flex-col">

    <div class="flex items-center gap-3 p-4 border-b">
        <img src="{{ $logo }}" class="w-20 h-14 object-contain" alt="Logo">
        <h1 class="font-semibold text-gray-800">Pembayaran Berhasil</h1>
    </div>

    <div class="flex-1 flex flex-col items-center justify-center p-6 text-center">

        <dotlottie-wc
            src="https://lottie.host/8cc5195f-7b9d-4036-8150-1b4b8202320d/OYMU2rV4er.lottie"
            autoplay
            loop
            style="width: 220px;height: 220px">
        </dotlottie-wc>

        <h2 class="text-2xl font-bold text-green-600 mt-4 mb-2">
            Terima Kasih
        </h2>

        <p class="text-gray-600 max-w-xs mb-6">
            Donasi Anda telah berhasil diproses.
        </p>

        <div class="w-full bg-green-50 border border-green-200 rounded-xl p-4 text-left text-sm space-y-2">
            <div class="flex justify-between">
                <span>Invoice</span>
                <span class="font-medium">{{ $transaction->reference }}</span>
            </div>

            <div class="flex justify-between">
                <span>Program</span>
                <span class="font-medium">{{ $transaction->donasi->program->judul }}</span>
            </div>

            <div class="flex justify-between">
                <span>Metode</span>
                <span class="font-medium uppercase">
                    {{ $transaction->payment_method }}
                </span>
            </div>

            <div class="flex justify-between font-semibold text-green-700 border-t pt-2">
                <span>Total</span>
                <span>
                    Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <a href="/"
           class="mt-8 px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium shadow">
            Kembali ke Beranda
        </a>
    </div>

</div>

</body>
</html>
