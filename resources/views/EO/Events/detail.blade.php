@extends('layouts.eo')

@section('title', 'Detail Event - ' . $event->title)

@section('content')
<div class="container" style="max-width: 1000px; margin: 20px auto;">
    <a href="{{ route('eo.events.index') }}" class="btn btn-secondary" style="background: #ecebff; color: #5148c7; padding: 8px 16px; border-radius: 6px; text-decoration: none; display: inline-block; margin-bottom: 20px; font-weight: bold;">
        ← Kembali ke Daftar Event
    </a>

    <div class="card" style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 3px 12px rgba(0,0,0,0.04); margin-bottom: 25px;">
        @if ($event->banner_image)
            <img
                src="{{ asset('storage/' . $event->banner_image) }}"
                alt="{{ $event->title }}"
                style="width: 100%; max-height: 400px; object-fit: cover;"
            >
        @endif

        <div style="padding: 25px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                @if ($category)
                    <span style="background: #ecebff; color: #5148c7; padding: 6px 14px; border-radius: 20px; font-weight: bold; font-size: 13px;">
                        {{ $category->name }}
                    </span>
                @endif

                <span class="status status-{{ $event->status }}" style="padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: bold; background: #fff3d8; color: #946200;">
                    Status: {{ ucfirst($event->status) }}
                </span>
            </div>

            <h1 style="margin-top: 10px; margin-bottom: 15px;">{{ $event->title }}</h1>

            <p style="color: #555; line-height: 1.6;">{{ $event->description }}</p>

            <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; color: #333;">
                <p><strong>Waktu Mulai:</strong> {{ \Carbon\Carbon::parse($event->start_time)->format('d M Y, H:i') }}</p>
                <p><strong>Waktu Selesai:</strong> {{ \Carbon\Carbon::parse($event->end_time)->format('d M Y, H:i') }}</p>
                <p><strong>Lokasi Tempat:</strong> {{ $event->venue_name }}, {{ $event->city }}</p>
                <p><strong>Alamat Lengkap:</strong> {{ $event->address }}</p>
            </div>
        </div>
    </div>

    <h2 style="margin-bottom: 18px;">Pilihan Tiket & Kuota</h2>

    @forelse ($tickets as $ticket)
        <div style="background: white; border-radius: 10px; padding: 18px 25px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
            <div>
                <h4 style="margin: 0 0 6px 0;">{{ $ticket->name }}</h4>
                <p style="margin: 0; color: #777b8d; font-size: 14px;">
                    Kuota: {{ $ticket->quota }} tiket | Terjual: {{ $ticket->sold_quota ?? 0 }} tiket
                </p>
            </div>

            <h4 style="margin: 0; color: #635bdb;">
                @if ($ticket->price > 0)
                    Rp {{ number_format($ticket->price, 0, ',', '.') }}
                @else
                    Gratis
                @endif
            </h4>
        </div>
    @empty
        <p style="color: #777b8d;">Belum ada jenis tiket yang dibuat untuk event ini.</p>
    @endforelse
</div>
@endsection