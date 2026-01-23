<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Instruksi Pembayaran</title>
</head>
<body>

<h2>Instruksi Pembayaran</h2>

<p>Silakan selesaikan pembayaran berikut:</p>

<ul>
    <li><b>Metode:</b> {{ strtoupper($transaction->payment_type) }}</li>
    <li><b>Nominal:</b>
        Rp {{ number_format($transaction->amount,0,',','.') }}</li>
</ul>

@if($transaction->payment_type === 'bca_va')
    <p><b>Virtual Account BCA:</b></p>
    <h3>{{ $transaction->payment_code }}</h3>
@endif

@if($transaction->payment_type === 'qris')
    <img src="{{ $transaction->qr_url }}" width="220">
@endif

<a href="{{ route('payment.pending', $transaction->reference) }}">
    Saya sudah bayar
</a>

</body>
</html>
