@extends('layouts.eo')

@section('title', 'Event Saya - M!VENT')

@section('content')
<div class="header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
    <div>
        <h1 style="margin: 0 0 8px;">Event Saya</h1>
        <p style="color: #777b8d; margin: 0;">Kelola draf event, tiket, kuota, dan status verifikasi.</p>
    </div>

    <a href="{{ route('eo.events.create') }}" class="btn btn-primary">
        + Buat Event
    </a>
</div>

@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

@if (session('error'))
<div class="alert alert-error">{{ session('error') }}</div>
@endif

@forelse ($events as $event)
<article class="event-card">
    <div class="event-top">
        <div>
            <h2>{{ $event->title }}</h2>
            <div class="event-meta">
                <div><strong>Lokasi:</strong> {{ $event->venue_name }}, {{ $event->city }}</div>
                <div><strong>Mulai:</strong> {{ \Carbon\Carbon::parse($event->start_time)->format('d M Y, H:i') }}</div>
                <div><strong>Selesai:</strong> {{ \Carbon\Carbon::parse($event->end_time)->format('d M Y, H:i') }}</div>
            </div>
        </div>
        <div>
            <span class="status status-{{ $event->status }}">
                {{ ucfirst($event->status) }}
            </span>
        </div>
    </div>

    <div class="event-actions">
        {{-- Tombol Detail Event --}}
        <a href="{{ route('events.show', $event->slug) }}" class="btn btn-secondary">
            Lihat Detail
        </a>

        @if ($event->status === 'draft' || $event->status === 'rejected')
        <a href="{{ route('eo.events.edit', $event->id) }}" class="btn btn-secondary">
            Edit Event
        </a>
        @endif

        @if ($event->status === 'draft')
        <form action="{{ route('eo.events.submit', $event->id) }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-submit" onclick="return confirm('Ajukan event ini untuk diverifikasi admin?')">
                Ajukan Verifikasi
            </button>
        </form>
        @endif
    </div>
</article>
@empty
<div class="empty">
    <h2>Belum Ada Event</h2>
    <p>Kamu belum membuat event. Mulai buat event pertamamu sekarang.</p>
    <a href="{{ route('eo.events.create') }}" class="btn btn-primary">Buat Event Pertama</a>
</div>
@endforelse
@endsection