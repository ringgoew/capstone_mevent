<!DOCTYPE html>
<html>

<head>
    <title>Pembayaran Gagal</title>
</head>

<body>
    <div style="text-align: center; margin-top: 50px;">
        <h1 style="color: red;">Pembayaran Gagal / Dibatalkan ❌</h1>
        <p>Maaf, transaksi untuk Order ID <b>{{ $orderId }}</b> tidak dapat diproses atau telah kadaluarsa.</p>
        <a href="/checkout">Coba Bayar Lagi</a>
    </div>
</body>

</html>
