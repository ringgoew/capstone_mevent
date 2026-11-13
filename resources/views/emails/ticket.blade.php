<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>E-Ticket - {{ $order->order_code }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #121212;
            color: #ffffff;
            margin: 0;
            padding: 20px;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #1a1a1a;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #333333;
        }
        .header {
            padding: 20px;
            background-color: #1e1e1e;
            border-bottom: 1px solid #333333;
        }
        .header h2 {
            margin: 0;
            color: #ccff00; /* Hijau neon */
            font-size: 20px;
        }
        .content {
            padding: 20px;
        }
        .ticket-box {
            background-color: #0b0b0b;
            border: 2px solid #ccff00;
            border-radius: 6px;
            margin-bottom: 20px;
            overflow: hidden;
        }
        .ticket-header {
            background-color: #141414;
            padding: 10px 15px;
            font-size: 11px;
            color: #aaaaaa;
            border-bottom: 1px solid #222222;
            display: flex;
            justify-content: space-between;
        }
        .ticket-body {
            padding: 20px;
            display: table;
            width: 100%;
            box-sizing: border-box;
        }
        .ticket-left {
            display: table-cell;
            vertical-align: top;
            width: 65%;
        }
        .ticket-right {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            width: 35%;
            border-left: 1px dashed #333333;
            padding-left: 15px;
        }
        .event-title {
            font-size: 22px;
            font-weight: bold;
            color: #ccff00;
            margin: 5px 0 15px 0;
            text-transform: uppercase;
        }
        .info-label {
            font-size: 10px;
            color: #888888;
            text-transform: uppercase;
            margin-bottom: 2px;
        }
        .info-value {
            font-size: 13px;
            font-weight: bold;
            color: #ffffff;
            margin-bottom: 10px;
        }
        .badge {
            background-color: #ccff00;
            color: #000000;
            font-size: 10px;
            font-weight: bold;
            padding: 4px 8px;
            border-radius: 4px;
            text-transform: uppercase;
            display: inline-block;
            margin-bottom: 10px;
        }
        .qr-placeholder {
            background-color: #ffffff;
            padding: 10px;
            display: inline-block;
            border-radius: 4px;
        }
        .qr-placeholder img {
            width: 90px;
            height: 90px;
            display: block;
        }
        .footer-note {
            font-size: 11px;
            color: #888888;
            line-height: 1.4;
            margin-top: 15px;
            border-top: 1px solid #2a2a2a;
            padding-top: 12px;
        }
    </style>
</head>
<body>

    <div class="email-container">
        <!-- Header Email -->
        <div class="header">
            <h2>Here's Your Ticket! 🎉</h2>
            <p style="font-size: 13px; color: #bbbbbb; margin: 5px 0 0 0;">Halo {{ $order->customer_name }}, pembayaranmu telah berhasil dikonfirmasi.</p>
        </div>

        <!-- Bagian Konten Tiket -->
        <div class="content">
            <div class="ticket-box">
                <!-- Baris Informasi Pembelian (Atas Tiket) -->
                <div class="ticket-header">
                    <span>PURCHASED BY: <strong>{{ $order->customer_name }}</strong></span>
                    <span>ORDER ID: <strong>{{ $order->order_code }}</strong></span>
                </div>

                <!-- Bagian Utama Tiket -->
                <div class="ticket-body">
                    <div class="ticket-left">
                        <div class="info-label">Event</div>
                        <div class="event-title">{{ $order->event_title ?? 'Nama Event' }}</div>

                        <div class="info-label">Jadwal & Waktu</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($order->start_time ?? now())->format('F d, Y - H:i') }} WIB</div>

                        <div class="info-label">Lokasi Venue</div>
                        <div class="info-value" style="margin-bottom: 0;">{{ $order->venue_name ?? 'Lokasi Event' }}</div>
                    </div>

                    <div class="ticket-right">
                        <div class="badge">General Admission</div>
                        
                        <!-- QR Code yang berisi link verifikasi tiket untuk discan petugas -->
                        <div class="qr-placeholder">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ url('/admin/verify-ticket/' . $order->order_code) }}" alt="QR Code Tiket">
                        </div>
                        <div style="font-size: 10px; color: #888; margin-top: 5px;">Scan saat masuk</div>
                    </div>
                </div>
            </div>

            <div style="font-family: Arial, sans-serif; max-width: 480px; margin: 0 auto;">
    <h2>Pembayaran Berhasil 🎉</h2>
    <p>Halo {{ $order->customer_name }}, tiket Anda untuk <strong>{{ $order->event_title }}</strong> sudah aktif.</p>

    <p>
        <strong>Kode Order:</strong> {{ $order->order_code }}<br>
        <strong>Tempat:</strong> {{ $order->venue_name }}<br>
        <strong>Waktu:</strong> {{ \Carbon\Carbon::parse($order->start_time)->translatedFormat('d F Y, H:i') }}
    </p>

    <p>Tunjukkan QR code ini di pintu masuk:</p>
    {{-- embedData membuat gambar inline (CID), lebih andal daripada data URI di Gmail/Outlook --}}
    <img src="{{ $message->embedData($qrPng, 'qr-tiket.png', 'image/png') }}"
         alt="QR Tiket" width="250" height="250">
</div>


            <!-- Catatan Kaki / Instruksi -->
            <div class="footer-note">
                <strong>Penting:</strong> Harap tunjukkan e-ticket atau QR Code di atas kepada petugas saat memasuki area event. Tiket ini hanya berlaku untuk satu kali penukaran.
            </div>
        </div>
    </div>

</body>
</html>