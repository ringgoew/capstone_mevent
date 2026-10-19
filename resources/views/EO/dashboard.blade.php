<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard EO - Event Management</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f5f6f8;
            color: #222;
        }

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 240px;
            height: 100vh;
            background-color: #111;
            color: white;
            padding: 25px 18px;
            overflow-y: auto;
            z-index: 1000;
        }

        .logo {
            font-size: 21px;
            font-weight: bold;
            margin-bottom: 35px;
            padding-left: 10px;
        }

        .sidebar a {
            display: block;
            color: #ddd;
            text-decoration: none;
            padding: 11px 13px;
            margin-bottom: 4px;
            border-radius: 7px;
            font-size: 14px;
            cursor: pointer;
            position: relative;
            z-index: 1001;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #333;
            color: white;
        }

        .menu-title {
            color: #888;
            font-size: 11px;
            margin: 20px 10px 8px;
            text-transform: uppercase;
        }

        /* MAIN */
        .main {
            margin-left: 240px;
            padding: 35px 45px;
            min-height: 100vh;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
        }

        .topbar h1 {
            font-size: 28px;
        }

        .subtitle {
            color: #777;
            margin-top: 7px;
            font-size: 14px;
        }

        .profile {
            background-color: white;
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 14px;
        }

        /* NOTIFIKASI */
        .alert {
            padding: 13px 16px;
            margin-bottom: 20px;
            border-radius: 7px;
            font-size: 14px;
        }

        .alert-success {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .alert-error {
            background-color: #ffebee;
            color: #c62828;
        }

        /* STATISTIK */
        .stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 30px;
        }

        .stat-card {
            background-color: white;
            padding: 22px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .stat-card p {
            color: #777;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .stat-card h2 {
            font-size: 24px;
            overflow-wrap: anywhere;
        }

        /* SECTION */
        .section {
            background-color: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .section-header h2 {
            font-size: 20px;
        }

        .btn {
            display: inline-block;
            background-color: #111;
            color: white;
            padding: 10px 15px;
            border-radius: 7px;
            text-decoration: none;
            font-size: 13px;
            white-space: nowrap;
        }

        .btn:hover {
            background-color: #333;
        }

        /* EVENT */
        .event-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            padding: 18px 0;
            border-bottom: 1px solid #eee;
        }

        .event-item:last-child {
            border-bottom: none;
        }

        .event-info {
            min-width: 0;
        }

        .event-info h3 {
            margin-bottom: 8px;
            overflow-wrap: anywhere;
        }

        .event-info p {
            color: #777;
            font-size: 13px;
            margin-bottom: 5px;
            line-height: 1.5;
        }

        .event-status {
            background-color: #e8f5e9;
            color: #2e7d32;
            padding: 6px 11px;
            border-radius: 15px;
            font-size: 12px;
            white-space: nowrap;
        }

        .status-pending {
            background-color: #fff3e0;
            color: #e65100;
        }

        .status-rejected {
            background-color: #ffebee;
            color: #c62828;
        }

        .status-draft {
            background-color: #eeeeee;
            color: #555;
        }

        .status-cancelled {
            background-color: #ffebee;
            color: #c62828;
        }

        .empty-state {
            text-align: center;
            padding: 35px 15px;
            color: #777;
            font-size: 14px;
        }

        .detail-btn {
            display: inline-block;
            margin-top: 7px;
            color: #222;
            font-size: 13px;
            text-decoration: none;
        }

        .detail-btn:hover {
            text-decoration: underline;
        }

        /* RESPONSIVE */
        @media (max-width: 1100px) {
            .stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .main {
                padding: 30px 25px;
            }
        }

        @media (max-width: 700px) {
            .sidebar {
                width: 190px;
                padding: 20px 12px;
            }

            .main {
                margin-left: 190px;
                padding: 25px 15px;
            }

            .topbar {
                align-items: flex-start;
                flex-direction: column;
            }

            .topbar h1 {
                font-size: 23px;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            .section {
                padding: 18px;
            }

            .section-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .event-item {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

    
<!-- SIDEBAR -->
<aside class="sidebar">

    <div class="logo">
        Event Management
    </div>

    <a href="{{ route('eo.dashboard') }}" class="active">
        Dashboard
    </a>

    <a href="{{ route('eo.events.index') }}">
        Event Saya
    </a>

    <a href="{{ route('eo.events.create') }}">
        Buat Event
    </a>

    <a href="{{ route('eo.dashboard') }}">
        Laporan
    </a>

    <a href="{{ route('eo.dashboard') }}">
        Payout
    </a>

    <div class="menu-title">
        Akun
    </div>

    <a href="{{ route('eo.dashboard') }}">
        Profil &amp; Dokumen
    </a>

    <a href="{{ route('home') }}">
        Logout
    </a>

</aside>

    <!-- MAIN CONTENT -->
    <main class="main">

        <!-- TOPBAR -->
        <div class="topbar">
            <div>
                <h1>Dashboard EO</h1>

                <p class="subtitle">
                    Kelola event dan pantau aktivitas event kamu.
                </p>
            </div>

            <div class="profile">
                {{ session('eo_nama', 'Event Organizer') }}
            </div>
        </div>

        <!-- NOTIFIKASI -->
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

        @if ($errors->has('event'))
            <div class="alert alert-error">
                {{ $errors->first('event') }}
            </div>
        @endif

        <!-- STATISTIK -->
        <div class="stats">

            <div class="stat-card">
                <p>Total Event</p>
                <h2>{{ $stats['total_event'] ?? 0 }}</h2>
            </div>

            <div class="stat-card">
                <p>Tiket Terjual</p>
                <h2>{{ $stats['tiket_terjual'] ?? 0 }}</h2>
            </div>

            <div class="stat-card">
                <p>Total Pendapatan</p>
                <h2>
                    Rp{{ number_format($stats['pendapatan'] ?? 0, 0, ',', '.') }}
                </h2>
            </div>

            <div class="stat-card">
                <p>Peserta Hadir</p>
                <h2>{{ $stats['peserta_hadir'] ?? 0 }}</h2>
            </div>

        </div>

        <!-- EVENT SAYA -->
        <div class="section">

            <div class="section-header">
                <h2>Event Saya</h2>

                <a href="{{ route('eo.events.create') }}" class="btn">
                    + Buat Event
                </a>
            </div>

            @forelse ($events as $event)

                <div class="event-item">

                    <div class="event-info">

                        <h3>{{ $event->title ?? $event->nama ?? 'Event tanpa judul' }}</h3>

                        <p>
                            📅
                            @if (!empty($event->start_time))
                                {{ \Carbon\Carbon::parse($event->start_time)->format('d-m-Y H:i') }}
                            @elseif (!empty($event->tanggal))
                                {{ $event->tanggal }}
                            @else
                                Jadwal belum tersedia
                            @endif

                            &nbsp; • &nbsp;

                            📍
                            @if (!empty($event->venue_name))
                                {{ $event->venue_name }}{{ !empty($event->city) ? ', ' . $event->city : '' }}
                            @elseif (!empty($event->lokasi))
                                {{ $event->lokasi }}
                            @else
                                Lokasi belum tersedia
                            @endif
                        </p>

                        <p>
                            🎟 {{ $event->tiket_terjual ?? 0 }} tiket terjual
                        </p>

                        <a href="{{ route('eo.events.index') }}" class="detail-btn">
                            Lihat Detail →
                        </a>

                    </div>

                    @php
                        $status = $event->status ?? 'draft';

                        $statusClass = match ($status) {
                            'pending' => 'status-pending',
                            'rejected', 'cancelled' => 'status-rejected',
                            'draft' => 'status-draft',
                            default => '',
                        };
                    @endphp

                    <div class="event-status {{ $statusClass }}">
                        {{ ucfirst($status) }}
                    </div>

                </div>

            @empty

                <div class="empty-state">
                    <p>Belum ada event yang dibuat.</p>

                    <p style="margin-top: 8px;">
                        Klik tombol <strong>+ Buat Event</strong> untuk mulai membuat event pertamamu.
                    </p>
                </div>

            @endforelse

        </div>

    </main>

</body>
</html>