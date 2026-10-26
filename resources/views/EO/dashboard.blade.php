@extends('layouts.eo')

@section('title', 'Dashboard EO - M!VENT')

@section('content')

<!-- TOPBAR -->
<div class="topbar">
    <div>
        <h1>Dashboard EO</h1>
        <p class="subtitle">Kelola event dan pantau aktivitas penjualan tiket kamu.</p>
    </div>

    <div class="profile">
        {{ session('eo_nama', 'Event Organizer') }}
    </div>
</div>

<!-- NOTIFIKASI -->
@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

@if (session('error'))
<div class="alert alert-error">{{ session('error') }}</div>
@endif

<!-- KARTU STATISTIK -->
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
        <h2>Rp {{ number_format($stats['pendapatan'] ?? 0, 0, ',', '.') }}</h2>
    </div>

    <div class="stat-card">
        <p>Peserta Hadir</p>
        <h2>{{ $stats['peserta_hadir'] ?? 0 }}</h2>
    </div>
</div>

<!-- SECTION EVENT SAYA -->
<div class="section">
    <div class="section-header">
        <h2>Event Saya</h2>
        <a href="{{ route('eo.events.create') }}" class="btn">+ Buat Event</a>
    </div>

    @forelse ($events as $event)
    <div class="event-item">
        <div style="display: flex; align-items: center; gap: 20px; flex: 1;">
            @if (!empty($event->banner_image) || !empty($event->foto))
            <div class="event-image-wrapper">
                <img
                    src="{{ asset('storage/' . ($event->banner_image ?? $event->foto)) }}"
                    alt="{{ $event->title ?? $event->nama }}"
                    style="width: 130px; height: 85px; object-fit: cover; border-radius: 10px; border: 1px solid #e4e4e7;">
            </div>
            @endif

            <div class="event-info">
                <h3>{{ $event->title ?? $event->nama ?? 'Event tanpa judul' }}</h3>

                <p>
                    📅
                    @if (!empty($event->start_time))
                    {{ \Carbon\Carbon::parse($event->start_time)->translatedFormat('d F Y, H:i') }}
                    @elseif (!empty($event->tanggal))
                    {{ $event->tanggal }}
                    @else
                    Jadwal belum diatur
                    @endif

                    &nbsp; • &nbsp; 📍
                    @if (!empty($event->venue_name))
                    {{ $event->venue_name }}{{ !empty($event->city) ? ', ' . $event->city : '' }}
                    @elseif (!empty($event->lokasi))
                    {{ $event->lokasi }}
                    @else
                    Lokasi belum diatur
                    @endif
                </p>

                <p>🎟 {{ $event->tiket_terjual ?? 0 }} tiket terjual</p>

                <a href="{{ route('eo.events.show', $event->id) }}" class="detail-btn">
                    Lihat Detail →
                </a>
            </div>
        </div>

        @php
        $status = strtolower($event->status ?? 'draft');
        $statusClass = match ($status) {
        'published' => 'status-published',
        'pending' => 'status-pending',
        'rejected', 'cancelled' => 'status-rejected',
        default => 'status-draft',
        };
        @endphp

        <div class="event-status {{ $statusClass }}">
            {{ ucfirst($status) }}
        </div>
    </div>
    @empty
    <div style="text-align: center; padding: 40px; color: #71717a;">
        <p style="margin-bottom: 8px;">Belum ada event yang dibuat.</p>
        <p style="font-size: 13px;">Klik tombol <strong>+ Buat Event</strong> untuk membuat event pertamamu.</p>
    </div>
    @endforelse
</div>

@endsection