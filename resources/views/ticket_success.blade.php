<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Tiket M!VENT - {{ $order->order_code }}</title>
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
            padding: 40px 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .ticket-wrapper {
            max-width: 480px;
            width: 100%;
        }

        .ticket-card {
            background: #FFFFFF;
            border-radius: 20px;
            border: 1px solid var(--border-color);
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
            overflow: hidden;
            position: relative;
        }

        .ticket-top {
            padding: 30px 28px 24px;
            text-align: center;
            border-bottom: 2px dashed #CBD5E1;
            position: relative;
        }

        /* Notches for authentic ticket look */
        .ticket-top::before,
        .ticket-top::after {
            content: '';
            position: absolute;
            bottom: -14px;
            width: 26px;
            height: 26px;
            background-color: var(--bg-color);
            border-radius: 50%;
            z-index: 2;
        }

        .ticket-top::before {
            left: -14px;
        }

        .ticket-top::after {
            right: -14px;
        }

        .brand-badge {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.1em;
            color: var(--primary-dark);
            text-transform: uppercase;
            margin-bottom: 8px;
            display: block;
        }

        .ticket-title {
            font-size: 22px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 12px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 9999px;
            font-size: 13px;
            font-weight: 600;
            background-color: var(--success-bg);
            color: var(--success-text);
        }

        .qr-section {
            margin: 22px 0 10px;
            padding: 16px;
            background: #F8FAFC;
            border: 1px solid var(--border-color);
            border-radius: 14px;
            display: inline-block;
        }

        .qr-section img {
            display: block;
            margin: 0 auto;
            border-radius: 8px;
        }

        .order-code {
            margin-top: 10px;
            font-family: monospace;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.08em;
            color: var(--text-dark);
        }

        .ticket-bottom {
            padding: 26px 28px 30px;
        }

        .info-group {
            margin-bottom: 16px;
        }

        .info-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 15px;
            font-weight: 600;
            color: var(--text-dark);
        }

        .items-card {
            background: #F8FAFC;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 14px 16px;
            margin: 18px 0;
        }

        .items-card ul {
            list-style: none;
        }

        .items-card li {
            font-size: 14px;
            font-weight: 500;
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
        }

        .action-buttons {
            display: flex;
            gap: 12px;
            margin-top: 24px;
        }

        .btn {
            flex: 1;
            padding: 13px 18px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            background-color: var(--primary);
            color: #0C4A6E;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            color: #FFFFFF;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background-color: #FFFFFF;
            color: var(--text-dark);
            border: 1px solid var(--border-color);
        }

        .btn-secondary:hover {
            background-color: #F1F5F9;
        }

        @media print {
            body {
                background: #FFFFFF;
                padding: 0;
            }

            .action-buttons {
                display: none !important;
            }

            .ticket-card {
                box-shadow: none;
                border: 1px solid #CBD5E1;
            }
        }
    </style>
</head>

<body>

    <div class="ticket-wrapper">
        <div class="ticket-card">
            <div class="ticket-top">
                <span class="brand-badge">M!VENT Official E-Ticket</span>
                <h1 class="ticket-title">{{ $order->event_title }}</h1>
                @foreach ($e_tickets as $index => $ticket)
                    <div class="qr-section" style="display: block; margin: 14px auto; max-width: 240px;">
                        <div
                            style="font-size: 11px; font-weight: 700; color: #64748B; margin-bottom: 8px; text-transform: uppercase;">
                            Tiket #{{ $index + 1 }} • {{ strtoupper($ticket->status ?? 'VALID') }}
                        </div>
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ $ticket->ticket_code }}"
                            alt="QR Code Tiket {{ $ticket->ticket_code }}">
                        <div class="order-code">{{ $ticket->ticket_code }}</div>
                    </div>
                @endforeach
                <br>
                <div class="status-badge">
                    <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    STATUS: {{ strtoupper($order->status) }}
                </div>

            </div>

            <div class="ticket-bottom">
                <div class="info-group">
                    <div class="info-label">Nama Pemesan</div>
                    <div class="info-value">{{ $order->customer_name }}</div>
                </div>

                <div class="info-group">
                    <div class="info-label">Jadwal & Waktu</div>
                    <div class="info-value">
                        {{ \Carbon\Carbon::parse($order->start_time)->translatedFormat('l, d F Y - H:i') }} WIB</div>
                </div>

                <div class="info-group">
                    <div class="info-label">Lokasi / Venue</div>
                    <div class="info-value">{{ $order->venue_name }}</div>
                </div>

                <div class="items-card">
                    <div class="info-label" style="margin-bottom: 8px;">Rincian Tiket Masuk</div>
                    <ul>
                        @foreach ($items as $item)
                            <li>
                                <span>{{ $item->ticket_name }}</span>
                                <span style="font-weight: 700;">x{{ $item->quantity }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="action-buttons">
                    <a href="{{ url('/') }}" class="btn btn-secondary">
                        ← Beranda
                    </a>
                    <button onclick="window.print()" class="btn btn-primary">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z"
                                clip-rule="evenodd" />
                        </svg>
                        Cetak / Simpan PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
