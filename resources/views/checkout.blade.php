<!DOCTYPE html>
<html>
<head>
    <title>Checkout Pembayaran</title>
    <!-- Script ini wajib untuk memunculkan popup Snap -->
    <script type="text/javascript"
            src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('midtrans.client_key') }}"></script>
</head>
<body>
    <h1>Tagihan Anda: Rp 50.000</h1>
    <button id="pay-button">Bayar Sekarang</button>

    <script type="text/javascript">
        // Ketika tombol ditekan, panggil Snap dengan token dari Controller
        document.getElementById('pay-button').onclick = function(){
            snap.pay('{{ $snapToken }}'); // Token dari controller masuk ke sini
        };
    </script>
</body>
</html>