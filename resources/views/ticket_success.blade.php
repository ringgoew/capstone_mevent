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
            max-width: 500px;
            margin: 0 auto;
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .qr-box {
            margin: 20px 0;
            padding: 15px;
            background: #fafafa;
            border: 1px dashed #ccc;
            display: inline-block;
        }

        .badge-paid {
            background: #28a745;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 14px;
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
        <h2 style="color: #333; margin-bottom: 5px;">E-TIKET RESMI</h2>
        <span class="badge-paid">STATUS: {{ strtoupper($order->status) }}</span>

        <!-- Generasi QR Code dari Order Code -->
        <div class="qr-box">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ $order->order_code }}"
                alt="QR Code Tiket">
            <p style="font-size: 12px; color: #666; margin-top: 5px; font-weight: bold;">{{ $order->order_code }}</p>
        </div>

        <div class="details">
            <p><strong>Nama Pemesan:</strong> {{ $order->customer_name }}</p>
            <p><strong>Event:</strong> {{ $order->event_title }}</p>
            <p><strong>Waktu:</strong> {{ $order->start_time }}</p>
            <p><strong>Lokasi:</strong> {{ $order->venue_name }}</p>
            <hr>
            <p><strong>Item Tiket:</strong></p>
            <ul>
                @foreach ($items as $item)
                    <li>{{ $item->ticket_name }} (x{{ $item->quantity }})</li>
                @endforeach
            </ul>
        </div>

        <button onclick="window.print()" style="margin-top: 15px; padding: 10px 20px; cursor: pointer;">Cetak / Simpan
            PDF</button>
    </div>

</body>

</html>
