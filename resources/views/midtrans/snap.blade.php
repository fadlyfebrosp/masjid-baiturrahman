<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran</title>

    @if($isProduction ?? false)
        <script
            src="https://app.midtrans.com/snap/snap.js"
            data-client-key="{{ $clientKey }}">
        </script>
    @else
        <script
            src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ $clientKey }}">
        </script>
    @endif
</head>
<body>

<script>
snap.pay('{{ $snapToken }}', {

    onSuccess: function (result) {
        // pembayaran sukses
        window.location.href =
            "{{ route('payment.success', ':reference') }}"
                .replace(':reference', result.order_id);
    },

    onPending: function (result) {
        // pembayaran pending
        window.location.href =
            "{{ route('payment.pending', ':reference') }}"
                .replace(':reference', result.order_id);
    },

    onError: function (result) {
        // pembayaran gagal
        window.location.href =
            "{{ route('payment.failed', ':reference') }}"
                .replace(':reference', result.order_id);
    },

    onClose: function () {
        /**
         * User menutup popup Snap
         * WAJIB tetap bawa reference dari server
         * agar transaksi bisa dilanjutkan
         */
        window.location.href =
            "{{ route('payment.pending', ':reference') }}"
                .replace(':reference', '{{ $reference }}');
    }

});
</script>

</body>
</html>
