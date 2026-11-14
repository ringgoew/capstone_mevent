<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>E-Tiket MIVENT - {{ $order->order_code }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            padding: 30px;
        }

        .ticket-card {
            max-width: 550px;
            margin: 0 auto;
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .ticket-item {
            background: #fafafa;
            border: 1px dashed #cbd5e1;
            border-radius: 8px;
            padding: 20px;
            margin: 15px 0;
        }

        .qr-box {
            margin: 10px 0;
        }

        .badge-paid {
            background: #28a745;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
        }

        .details {
            text-align: left;
            margin-top: 20px;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }
    </style>
</head>

<body>

    <div class="ticket-card">
        <h2 style="color: #333; margin-bottom: 5px;">E-TIKET RESMI EVENT</h2>
        <span class="badge-paid">STATUS: {{ strtoupper($order->status ?? 'PAID') }}</span>

        <div style="margin-top: 15px; font-size: 14px; color: #64748b;">
            <p><strong>Kode Transaksi:</strong> {{ $order->order_code }}</p>
            <p><strong>Nama Pemesan:</strong> {{ $order->customer_name }}</p>
            <p><strong>Event:</strong> {{ $order->event_title }}</p>
            <p><strong>Waktu:</strong> {{ $order->start_time }}</p>
            <p><strong>Lokasi:</strong> {{ $order->venue_name }}</p>
        </div>

        <div class="details">
            <h3 style="font-size: 16px; color: #1e293b; margin-bottom: 10px;">Daftar Tiket Anda:</h3>
            
            <!-- LOOPING SETIAP TIKET UNIK DARI TABEL e_tickets -->
            @foreach ($e_tickets as $index => $ticket)
                <div class="ticket-item">
                    <p style="margin: 0 0 5px 0; font-size: 13px; color: #64748b; font-weight: bold;">
                        TIKET KE-{{ $index + 1 }}
                    </p>
                    
                    <!-- QR Code digenerate langsung dari ticket_code database -->
                    <div class="qr-box">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ $ticket->ticket_code }}"
                            alt="QR Code Tiket">
                    </div>

                    <!-- Teks Kode Tiket yang Sinkron dengan Scanner Panitia -->
                    <p style="font-size: 15px; color: #2563eb; margin: 8px 0 0 0; font-weight: bold; font-family: monospace;">
                        {{ $ticket->ticket_code }}
                    </p>
                    <p style="font-size: 12px; color: #666; margin: 3px 0 0 0;">
                        Status: <strong>{{ strtoupper($ticket->status) }}</strong>
                    </p>
                </div>
            @endforeach
        </div>
            🖨️ Cetak / Simpan PDF
        <button onclick="window.print()" style="margin-top: 20px; padding: 12px 24px; background: #2563eb; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; font-size: 14px;">
         
        </button>
    </div>

</body>

</html>