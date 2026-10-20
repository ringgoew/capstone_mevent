@extends('layouts.eo')
@section('title', 'Dashboard EO')
@section('content')

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

                @if (!empty($event->foto))
            <div class="event-image-wrapper" style="margin-right: 20px;">
                <img src="{{ asset('assets/img/events/' . $event->foto) }}" 
                     alt="{{ $event->nama }}" 
                     style="width: 120px; height: 80px; object-fit: cover; border-radius: 8px;">
            </div>
        @endif
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


@endsection