<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran</title>

    <script
        src="{{ $production
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
        data-client-key="{{ $clientKey }}">
    </script>
</head>
<body>

<script>
snap.pay('{{ $snapToken }}', {

    onSuccess: function (result) {
        goStatus(result.order_id);
    },

    onPending: function (result) {
        goStatus(result.order_id);
    },

    onClose: function () {
        goStatus('{{ $reference }}');
    },

    onError: function (result) {
        window.location.href =
            "{{ route('payment.failed', ':ref') }}"
                .replace(':ref', result.order_id);
    }
});

function goStatus(ref) {
    window.location.href =
        "{{ route('payment.pending', ':ref') }}"
            .replace(':ref', ref);
}
</script>

</body>
</html>
