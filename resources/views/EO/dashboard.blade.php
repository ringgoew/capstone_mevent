@extends('layouts.eo')
@section('title', 'Dashboard EO')
@section('content')


    <aside class="sidebar">

        <div class="logo">
            Event Management
        </div>

        <a href="{{ route('eo.dashboard') }}" class="active">
            Dashboard
        </a>

        <a href="#">
            Event Saya
        </a>

        <a href="#">
            Buat Event
        </a>

        <a href="#">
            Tiket & Kuota
        </a>

        <a href="#">
            Penjualan Tiket
        </a>

        <a href="#">
            Data Peserta
        </a>

        <a href="#">
            Laporan
        </a>

        <a href="#">
            Payout
        </a>

        <div class="menu-title">
            Akun
        </div>

        <a href="#">
            Profil & Dokumen
        </a>

        <a href="{{ route('home') }}">
            Logout
        </a>

    </aside>

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
                EO Event POOKIEMAX
            </div>

        </div>


        <div class="stats">

            <div class="stat-card">
                <p>Total Event</p>
                <h2>{{ $stats['total_event'] }}</h2>
            </div>

            <div class="stat-card">
                <p>Tiket Terjual</p>
                <h2>{{ $stats['tiket_terjual'] }}</h2>
            </div>

            <div class="stat-card">
                <p>Total Pendapatan</p>
                <h2>
                    Rp{{ number_format($stats['pendapatan'], 0, ',', '.') }}
                </h2>
            </div>

            <div class="stat-card">
                <p>Peserta Hadir</p>
                <h2>{{ $stats['peserta_hadir'] }}</h2>
            </div>

        </div>


        <!-- EVENT SAYA -->
        <div class="section">

            <div class="section-header">

                <h2>Event Saya</h2>

                <a href="#" class="btn">
                    + Buat Event
                </a>

            </div>


            @foreach ($events as $event)

                <div class="event-item">

                    <div class="event-info">

                        <h3>{{ $event['nama'] }}</h3>

                        <p>
                            📅 {{ $event['tanggal'] }}
                            &nbsp; • &nbsp;
                            📍 {{ $event['lokasi'] }}
                        </p>

                        <p>
                            🎟 {{ $event['tiket_terjual'] }} tiket terjual
                        </p>

                        <a href="#" class="detail-btn">
                            Lihat Detail →
                        </a>

                    </div>

                    <div class="event-status">
                        {{ $event['status'] }}
                    </div>

                </div>

            @endforeach

        </div>
    </main>


@endsection