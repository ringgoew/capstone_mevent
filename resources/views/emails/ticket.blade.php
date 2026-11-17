<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Tiket M!VENT - {{ $order->order_code }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #F8FAFC;
            color: #0F172A;
            margin: 0;
            padding: 24px 16px;
        }
        .email-container {
            max-width: 580px;
            margin: 0 auto;
            background-color: #FFFFFF;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #E2E8F0;
            box-shadow: 0 4px 15px rgba(15, 23, 42, 0.05);
        }
        .header {
            padding: 24px 30px;
            background-color: #0F172A;
            color: #FFFFFF;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.02em;
        }
        .header h1 span {
            color: #38BDF8;
        }
        .content {
            padding: 28px 30px;
        }
        .greeting {
            font-size: 15px;
            color: #475569;
            margin-bottom: 20px;
            line-height: 1.5;
        }
        .ticket-box {
            background-color: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 14px;
            margin-bottom: 24px;
            overflow: hidden;
        }
        .ticket-header {
            background-color: #F1F5F9;
            padding: 12px 18px;
            font-size: 12px;
            font-weight: 600;
            color: #64748B;
            border-bottom: 1px solid #E2E8F0;
            display: flex;
            justify-content: space-between;
        }
        .ticket-body {
            padding: 20px 18px;
        }
        .event-title {
            font-size: 20px;
            font-weight: 700;
            color: #0F172A;
            margin: 0 0 14px 0;
        }
        .info-row {
            margin-bottom: 10px;
            font-size: 14px;
            line-height: 1.5;
        }
        .info-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748B;
            letter-spacing: 0.05em;
        }
        .info-value {
            font-size: 14px;
            font-weight: 600;
            color: #0F172A;
        }
        .qr-section {
            text-align: center;
            background: #FFFFFF;
            border: 1px dashed #CBD5E1;
            border-radius: 12px;
            padding: 18px;
            margin: 18px 0;
        }
        .qr-section img {
            display: block;
            margin: 0 auto;
        }
        .badge-verified {
            display: inline-block;
            background-color: #DCFCE7;
            color: #166534;
            font-size: 12px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 9999px;
            margin-bottom: 12px;
        }
        .footer-note {
            font-size: 12px;
            color: #64748B;
            line-height: 1.5;
            padding-top: 16px;
            border-top: 1px solid #E2E8F0;
        }
        .footer-brand {
            background-color: #F8FAFC;
            padding: 16px;
            text-align: center;
            font-size: 12px;
            color: #94A3B8;
            border-top: 1px solid #E2E8F0;
        }
    </style>
</head>
<body>

    <div class="email-container">
        <!-- Header Email -->
        <div class="header">
            <h1>M<span>!</span>VENT</h1>
        </div>

        <!-- Bagian Konten Tiket -->
        <div class="content">
            <p class="greeting">
                Halo <strong>{{ $order->customer_name }}</strong>,<br>
                Pembayaran Anda telah berhasil diverifikasi. Berikut adalah e-tiket resmi Anda:
            </p>

            <div class="ticket-box">
                <div class="ticket-header">
                    <span>PEMESAN: <strong>{{ $order->customer_name }}</strong></span>
                    <span>ORDER ID: <strong>{{ $order->order_code }}</strong></span>
                </div>

                <div class="ticket-body">
                    <span class="badge-verified">✓ TIKET VALID & AKTIF</span>
                    <h2 class="event-title">{{ $order->event_title ?? 'Nama Event' }}</h2>

                    <div class="info-row">
                        <div class="info-label">Jadwal & Waktu</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($order->start_time ?? now())->translatedFormat('l, d F Y - H:i') }} WIB</div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Lokasi / Venue</div>
                        <div class="info-value">{{ $order->venue_name ?? 'Lokasi Event' }}</div>
                    </div>

                    <div class="qr-section">
                        @if(isset($qrPng) && isset($message))
                            <img src="{{ $message->embedData($qrPng, 'qr-tiket.png', 'image/png') }}" alt="QR Tiket" width="180" height="180">
                        @else
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ $order->order_code }}" alt="QR Code Tiket" width="180" height="180">
                        @endif
                        <div style="margin-top: 8px; font-family: monospace; font-size: 14px; font-weight: 700; color: #0F172A;">
                            {{ $order->order_code }}
                        </div>
                        <div style="font-size: 12px; color: #64748B; margin-top: 4px;">Tunjukkan QR Code ini kepada petugas di pintu masuk (gate)</div>
                    </div>
                </div>
            </div>

            <div class="footer-note">
                <strong>Catatan Penting:</strong> Harap simpan e-tiket ini atau tangkapan layarnya di ponsel Anda. Tiket ini hanya sah untuk satu kali penukaran/pindai per kuota yang dibeli.
            </div>
        </div>

        <div class="footer-brand">
            &copy; {{ date('Y') }} M!VENT Event Management Platform. All rights reserved.
        </div>
    </div>

</body>
</html>
