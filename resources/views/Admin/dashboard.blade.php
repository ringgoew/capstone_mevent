@extends('layouts.admin')
@section('title', 'Dashboard Admin')
@section('content')

    <div class="header">
        <h1>Dashboard Admin</h1>
        <p>Selamat datang di halaman administrator.</p>
    </div>

    <!-- STATISTIC -->
    <div class="stats">
        <div class="stat-card">
            <div class="stat-title">Total Event</div>
            <div class="stat-number">{{ $data['total_event'] ?? 0 }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-title">Event Pending</div>
            <div class="stat-number">{{ $data['event_pending'] ?? 0 }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-title">Total EO</div>
            <div class="stat-number">{{ $data['total_eo'] ?? 0 }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-title">Tiket Terjual</div>
            <div class="stat-number">{{ $data['total_tiket'] ?? 0 }}</div>
        </div>
    </div>

    <!-- MENU ADMIN -->

    <div class="menu-container">


        <div class="menu-card">

            <h3>
                Verifikasi EO
            </h3>

            <a href="{{ route('admin.event-verification.index') }}" class="btn btn-dark" style="margin-top:12px">
                Periksa Event
            </a>

            <p>
                Admin dapat melakukan pemeriksaan
                terhadap pendaftaran Event Organizer.
            </p>

        </div>



        <div class="menu-card">
            <h3>Verifikasi Event</h3>
            <p>Admin dapat memeriksa event sebelum event dipublikasikan.</p>
        </div>

        <div class="menu-card">
            <h3>Laporan</h3>
            <p>Admin dapat melihat informasi dan laporan dari sistem.</p>
        </div>
    </div>

@endsection
