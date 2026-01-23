<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Menunggu Pembayaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
<div class="max-w-md mx-auto bg-white min-h-screen flex flex-col">

    <div class="p-4 border-b text-center font-semibold">
        Menunggu Pembayaran
    </div>

    <div class="flex-1 flex flex-col items-center justify-center p-6 text-center">
        <h2 class="text-xl font-bold text-yellow-500 mb-2">
            Memverifikasi Pembayaran
        </h2>

        <p class="text-gray-600 mb-4">
            Sistem akan otomatis memperbarui status pembayaran Anda.
        </p>

        <div class="bg-yellow-50 border rounded p-3 text-sm">
            Invoice: <strong>{{ $transaction->reference }}</strong><br>
            Total: Rp {{ number_format($transaction->amount, 0, ',', '.') }}
        </div>

        <p id="hint" class="text-xs text-gray-400 mt-4 hidden">
            Pembayaran sedang diproses oleh bank...
        </p>
    </div>
</div>

<script>
let tries = 0;
const maxTries = 60; // 3 menit

const interval = setInterval(async () => {
    tries++;

    try {
        const res = await fetch("{{ route('payment.status', $transaction->reference) }}");
        const data = await res.json();

        if (data.status === 'paid') {
            clearInterval(interval);
            window.location.href =
                "{{ route('payment.success', $transaction->reference) }}";
        }

        if (['expired', 'failed'].includes(data.status)) {
            clearInterval(interval);
            window.location.href =
                "{{ route('payment.failed', $transaction->reference) }}";
        }

        if (tries === 10) {
            document.getElementById('hint').classList.remove('hidden');
        }

        if (tries >= maxTries) {
            clearInterval(interval);
        }

    } catch (e) {
        console.error(e);
    }

}, 3000);
</script>
</body>
</html>
