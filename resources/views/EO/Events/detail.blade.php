@extends('layouts.main')

@section('title', $event->title . ' - M!VENT')

@section('content')
<div class="container py-5">
    <a href="{{ route('home') }}" class="btn btn-outline-secondary mb-4">
        ← Kembali ke Home
    </a>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        @if ($event->banner_image)
            <img
                src="{{ asset('storage/' . $event->banner_image) }}"
                alt="{{ $event->title }}"
                class="w-100"
                style="max-height: 400px; object-fit: cover;"
            >
        @endif

        <div class="card-body p-4">
            @if ($category)
                <span class="badge bg-primary">
                    {{ $category->name }}
                </span>
            @endif

            <h1 class="fw-bold mt-3">{{ $event->title }}</h1>

            <p>{{ $event->description }}</p>

            <p>
                <strong>Tanggal:</strong>
                {{ \Carbon\Carbon::parse($event->start_time)->translatedFormat('d F Y, H:i') }}
            </p>

            <p>
                <strong>Lokasi:</strong>
                {{ $event->venue_name }}, {{ $event->city }}
            </p>

            <p>
                <strong>Alamat:</strong>
                {{ $event->address }}
            </p>
        </div>
    </div>

    <h2 class="fw-bold mt-5 mb-3">Pilihan Tiket</h2>

    @forelse ($tickets as $ticket)
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h5 class="fw-bold">{{ $ticket->name }}</h5>
                    <p class="text-muted mb-0">
                        Kuota: {{ $ticket->quota }} tiket
                    </p>
                </div>

                <h5 class="text-primary fw-bold mb-0">
                    @if ($ticket->price > 0)
                        Rp {{ number_format($ticket->price, 0, ',', '.') }}
                    @else
                        Gratis
                    @endif
                </h5>
            </div>
        </div>
    @empty
        <p>Belum ada tiket untuk event ini.</p>
    @endforelse
</div>
@endsection