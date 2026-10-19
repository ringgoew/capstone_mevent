
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Saya - POOKIEMAX</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6fb;
            color: #292b43;
        }

        .navbar {
            background: #29264b;
            color: white;
            padding: 18px 7%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
        }

        .navbar h2 {
            margin: 0;
            font-size: 22px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
        }

        .container {
            max-width: 1100px;
            margin: 35px auto;
            padding: 0 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
        }

        .header h1 {
            margin: 0 0 8px;
        }

        .header p {
            color: #777b8d;
            margin: 0;
        }

        .btn {
            display: inline-block;
            padding: 10px 14px;
            border: none;
            border-radius: 7px;
            font-size: 13px;
            font-weight: bold;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-primary {
            background: #635bdb;
            color: white;
        }

        .btn-secondary {
            background: #ecebff;
            color: #5148c7;
        }

        .btn-disabled {
            background: #e8e8ed;
            color: #777;
            cursor: not-allowed;
        }

        .btn-submit {
            background: #e3f7e9;
            color: #23763b;
        }

        .alert {
            padding: 14px 16px;
            border-radius: 8px;
            margin-bottom: 18px;
        }

        .alert-success {
            background: #e8f8ed;
            color: #226b37;
        }

        .alert-error {
            background: #fff0f0;
            color: #a52525;
        }

        .event-card {
            background: white;
            border-radius: 12px;
            padding: 22px;
            margin-bottom: 20px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.04);
        }

        .event-top {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            align-items: flex-start;
            margin-bottom: 18px;
        }

        .event-top h2 {
            margin: 0 0 8px;
            font-size: 21px;
        }

        .event-meta {
            color: #777b8d;
            font-size: 13px;
            line-height: 1.8;
        }

        .status {
            display: inline-block;
            padding: 7px 11px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            white-space: nowrap;
        }

        .status-draft {
            background: #ecebff;
            color: #5148c7;
        }

        .status-pending {
            background: #fff3d8;
            color: #946200;
        }

        .status-published {
            background: #e0f7e8;
            color: #23763b;
        }

        .status-rejected {
            background: #ffe6e6;
            color: #b52d2d;
        }

        .status-other {
            background: #eceef2;
            color: #555;
        }

        .ticket-section {
            border-top: 1px solid #eeeef4;
            padding-top: 15px;
            margin-top: 15px;
        }

        .ticket-section h3 {
            font-size: 16px;
            margin: 0 0 12px;
        }

        .ticket-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
            gap: 12px;
        }

        .ticket-item {
            border: 1px solid #e3e4ef;
            border-radius: 8px;
            padding: 14px;
        }

        .ticket-item h4 {
            margin: 0 0 8px;
        }

        .ticket-item p {
            margin: 5px 0;
            color: #777b8d;
            font-size: 13px;
        }

        .event-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 18px;
        }

        .empty {
            background: white;
            border-radius: 12px;
            text-align: center;
            padding: 55px 20px;
        }

        .empty h2 {
            margin-top: 0;
        }

        .empty p {
            color: #777b8d;
            margin-bottom: 20px;
        }

        .rejection {
            background: #fff2f2;
            color: #a52525;
            padding: 12px;
            border-radius: 7px;
            margin-top: 12px;
            font-size: 13px;
        }

        @media (max-width: 650px) {
            .header,
            .event-top {
                flex-direction: column;
            }

            .event-actions {
                flex-direction: column;
            }

            .event-actions .btn,
            .header .btn {
                text-align: center;
            }
        }
    </style>
</head>

<body>

    <nav class="navbar">
        <h2>M!VENT</h2>
        <a href="{{ route('eo.dashboard') }}">Dashboard EO</a>
    </nav>

    <main class="container">

        <div class="header">
            <div>
                <h1>Event Saya</h1>
                <p>Kelola draf event, tiket, kuota, dan status verifikasi.</p>
            </div>

            <a href="{{ route('eo.events.create') }}" class="btn btn-primary">
                + Buat Event
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @forelse ($events as $event)

            <article class="event-card">

                <div class="event-top">
                    <div>
                        <h2>{{ $event->title }}</h2>

                        <div class="event-meta">
                            <div>
                                <strong>Lokasi:</strong>
                                {{ $event->venue_name }}, {{ $event->city }}
                            </div>

                            <div>
                                <strong>Mulai:</strong>
                                {{ \Carbon\Carbon::parse($event->start_time)->format('d M Y, H:i') }}
                            </div>

                            <div>
                                <strong>Selesai:</strong>
                                {{ \Carbon\Carbon::parse($event->end_time)->format('d M Y, H:i') }}
                            </div>

                            <div>
                                <strong>Dibuat:</strong>
                                {{ \Carbon\Carbon::parse($event->created_at)->format('d M Y, H:i') }}
                            </div>
                        </div>
                    </div>

                    <div>
                        @php
                            $statusLabels = [
                                'draft' => 'Draf',
                                'pending' => 'Menunggu Verifikasi',
                                'published' => 'Dipublikasikan',
                                'rejected' => 'Ditolak',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                            ];

                            $statusClass = [
                                'draft' => 'status-draft',
                                'pending' => 'status-pending',
                                'published' => 'status-published',
                                'rejected' => 'status-rejected',
                            ];
                        @endphp

                        <span class="status {{ $statusClass[$event->status] ?? 'status-other' }}">
                            {{ $statusLabels[$event->status] ?? ucfirst($event->status) }}
                        </span>
                    </div>
                </div>

                @if ($event->status === 'rejected' && $event->rejection_reason)
                    <div class="rejection">
                        <strong>Alasan penolakan:</strong>
                        {{ $event->rejection_reason }}
                    </div>
                @endif

                <div class="ticket-section">
                    <h3>Jenis Tiket dan Kuota</h3>

                    @php
                        $tickets = DB::table('ticket_types')
                            ->where('event_id', $event->id)
                            ->orderBy('id')
                            ->get();
                    @endphp

                    @if ($tickets->isNotEmpty())
                        <div class="ticket-list">
                            @foreach ($tickets as $ticket)
                                <div class="ticket-item">
                                    <h4>{{ $ticket->name }}</h4>

                                    <p>
                                        Harga:
                                        <strong>
                                            @if ((float) $ticket->price == 0)
                                                Gratis
                                            @else
                                                Rp{{ number_format((float) $ticket->price, 0, ',', '.') }}
                                            @endif
                                        </strong>
                                    </p>

                                    <p>Kuota: {{ $ticket->quota }}</p>

                                    <p>
                                        Terjual: {{ $ticket->sold_quota }}
                                    </p>

                                    <p>
                                        Dipesan: {{ $ticket->reserved_quota }}
                                    </p>

                                    <p>
                                        Maks. per transaksi:
                                        {{ $ticket->max_per_order }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="event-meta">Belum ada jenis tiket.</p>
                    @endif
                </div>

                <div class="event-actions">
                    @if ($event->status === 'draft')
                        <form
                            action="{{ route('eo.events.submit', $event->id) }}"
                            method="POST"
                            onsubmit="return confirm('Ajukan event ini untuk diverifikasi admin?')"
                        >
                            @csrf

                            <button type="submit" class="btn btn-submit">
                                Ajukan Verifikasi
                            </button>
                        </form>
                    @elseif ($event->status === 'pending')
                        <button type="button" class="btn btn-disabled" disabled>
                            Menunggu Verifikasi Admin
                        </button>
                    @elseif ($event->status === 'published')
                        <button type="button" class="btn btn-disabled" disabled>
                            Event Dipublikasikan
                        </button>
                    @elseif ($event->status === 'rejected')
                        <button type="button" class="btn btn-disabled" disabled>
                            Perlu Perbaikan
                        </button>
                    @endif
                </div>

            </article>

        @empty

            <div class="empty">
                <h2>Belum Ada Event</h2>
                <p>Kamu belum membuat event. Mulai buat event pertamamu sekarang.</p>

                <a href="{{ route('eo.events.create') }}" class="btn btn-primary">
                    Buat Event Pertama
                </a>
            </div>

        @endforelse

    </main>

</body>
</html>
