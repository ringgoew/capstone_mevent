<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Pembayaran - M!VENT</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
    <style>
        :root {
            --bg-color: #F8FAFC;
            --primary: #38BDF8;
            --primary-dark: #0EA5E9;
            --text-dark: #0F172A;
            --text-muted: #64748B;
            --border-color: #E2E8F0;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Instrument Sans', system-ui, -apple-system, sans-serif;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .pay-card {
            background: #FFFFFF;
            border-radius: 20px;
            border: 1px solid var(--border-color);
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08);
            max-width: 440px;
            width: 100%;
            padding: 36px 30px;
            text-align: center;
        }

        .icon-circle {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            background-color: #E0F2FE;
            color: #0284C7;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .title {
            font-size: 22px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .desc {
            font-size: 14px;
            color: var(--text-muted);
            line-height: 1.5;
            margin-bottom: 24px;
        }

        .btn-pay {
            width: 100%;
            padding: 14px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            background-color: var(--primary);
            color: #0C4A6E;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-pay:hover {
            background-color: var(--primary-dark);
            color: #FFFFFF;
        }
    </style>
</head>
<body>

    <div class="pay-card">
        <div class="icon-circle">
            <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
        </div>

        <h1 class="title">Selesaikan Pembayaran</h1>
        <p class="desc">
            Klik tombol di bawah untuk membuka popup pembayaran Midtrans Snap yang aman.
        </p>

        <button id="pay-button" class="btn-pay">
            Bayar Sekarang
        </button>
    </div>

    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function() {
            snap.pay('{{ $snapToken }}');
        };
    </script>
</body>
</html>
