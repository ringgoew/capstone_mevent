<!DOCTYPE html>
<html lang="en">
<head>
    <title>Checkout Pembayaran</title>
    <script type="text/javascript"
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
</head>
<body>
    <h1>Tagihan Anda: Rp.50.000</h1>
    <button id="pay-button">Bayar Sekarang</button>

    <script type="text/javascript">
    // ketika tombol ditekan, Panggil Snap dengan token Dari controller
    document.getElementById('pay-button').onclick = function(){
        snap.pay('{{ $snapToken }}');
    };
    </script>
</body>
</html>
