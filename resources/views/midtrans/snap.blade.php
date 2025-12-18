<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memproses Pembayaran...</title>

    <script
        type="text/javascript"
        src="{{ $production ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
        data-client-key="{{ $clientKey }}">
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result){
                    window.location.href = "/payment/success";
                },
                onPending: function(result){
                    window.location.href = "/payment/pending";
                },
                onError: function(result){
                    window.location.href = "/payment/failed";
                },
                onClose: function(){
                    alert('Anda menutup popup pembayaran.');
                }
            });
        });
    </script>

    <style>
        body {
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
            font-family:Arial;
        }
    </style>
</head>

<body>
    <h2>Memproses pembayaran... Mohon tunggu</h2>
</body>

</html>
