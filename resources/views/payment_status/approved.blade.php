<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil - M!VENT</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <style>
        :root {
            --bg-color: #F8FAFC;
            --primary: #38BDF8;
            --primary-dark: #0EA5E9;
            --text-dark: #0F172A;
            --text-muted: #64748B;
            --border-color: #E2E8F0;
            --success-bg: #DCFCE7;
            --success-text: #166534;
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

        .status-card {
            background: #FFFFFF;
            border-radius: 20px;
            border: 1px solid var(--border-color);
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08);
            max-width: 480px;
            width: 100%;
            padding: 40px 32px;
            text-align: center;
        }

        .icon-circle {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background-color: var(--success-bg);
            color: var(--success-text);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .title {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .desc {
            font-size: 15px;
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 24px;
        }

        .order-badge {
            background: #F1F5F9;
            border: 1px solid var(--border-color);
            padding: 10px 16px;
            border-radius: 10px;
            font-family: monospace;
            font-size: 14px;
            font-weight: 700;
            color: var(--text-dark);
            display: inline-block;
            margin-bottom: 28px;
        }

        .btn-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 13px 20px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background-color: var(--primary);
            color: #0C4A6E;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            color: #FFFFFF;
        }

        .btn-secondary {
            background-color: #FFFFFF;
            color: var(--text-muted);
            border: 1px solid var(--border-color);
        }

        .btn-secondary:hover {
            background-color: #F8FAFC;
            color: var(--text-dark);
        }
    </style>
</head>
<body>

    <div class="status-card">
        <div class="icon-circle">
            <svg width="36" height="36" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
        </div>

        <h1 class="title">Pembayaran Berhasil!</h1>
        <p class="desc">
            Terima kasih! Tiket untuk pesanan Anda telah dikonfirmasi dan siap digunakan.
        </p>

        <div class="order-badge">
            ORDER ID: {{ $orderId }}
        </div>

        <div class="btn-group">
            <a href="{{ route('orders.ticket', $orderId) }}" class="btn btn-primary">
                Lihat & Cetak E-Tiket
            </a>
            <a href="{{ route('home') }}" class="btn btn-secondary">
                Kembali ke Beranda
            </a>
        </div>
    </div>

</body>
</html>
