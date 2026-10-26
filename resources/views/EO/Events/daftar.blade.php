@extends('layouts.eo')

@section('title', 'Event Saya - M!VENT')

@section('content')
<div class="my-events-page">

    <div class="my-events-header">
        <div>
            <span class="my-events-eyebrow">M!VENT ORGANIZER</span>
            <h1>Event Saya</h1>
            <p>Kelola draf event, tiket, kuota, dan status verifikasi.</p>
        </div>

        <a href="{{ route('eo.events.create') }}"
           class="my-events-btn my-events-btn-primary">
            <span>+</span> Buat Event
        </a>
    </div>

    @if (session('success'))
        <div class="my-events-alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="my-events-alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    <div class="my-events-list">
        @forelse ($events as $event)
            <article class="my-event-card">

                <div class="my-event-card-top">
                    <div class="my-event-title-group">
                        <div class="my-event-icon">E</div>

                        <div>
                            <h2>{{ $event->title }}</h2>
                            <span class="my-event-slug">
                                {{ $event->slug }}
                            </span>
                        </div>
                    </div>

                    <span class="my-event-status status-{{ strtolower($event->status) }}">
                        {{ ucfirst($event->status) }}
                    </span>
                </div>

                <div class="my-event-info">
                    <div class="my-event-info-item">
                        <span class="my-event-info-label">Lokasi</span>
                        <strong>
                            {{ $event->venue_name }}
                            @if ($event->city)
                                , {{ $event->city }}
                            @endif
                        </strong>
                    </div>

                    <div class="my-event-info-item">
                        <span class="my-event-info-label">Mulai</span>
                        <strong>
                            {{ \Carbon\Carbon::parse($event->start_time)->format('d M Y, H:i') }}
                        </strong>
                    </div>

                    <div class="my-event-info-item">
                        <span class="my-event-info-label">Selesai</span>
                        <strong>
                            {{ \Carbon\Carbon::parse($event->end_time)->format('d M Y, H:i') }}
                        </strong>
                    </div>
                </div>

                @if ($event->status === 'rejected' && !empty($event->rejection_reason))
                    <div class="my-event-rejection">
                        <strong>Alasan penolakan</strong>
                        <p>{{ $event->rejection_reason }}</p>
                    </div>
                @endif

                <div class="my-event-actions">
                    <a href="{{ route('eo.events.show', $event->id) }}"
   class="my-events-btn my-events-btn-outline">
    Lihat Detail
</a>

                    @if (in_array($event->status, ['draft', 'rejected']))
                        <a href="{{ route('eo.events.edit', $event->id) }}"
                           class="my-events-btn my-events-btn-outline">
                            Edit Event
                        </a>
                    @endif

                    @if ($event->status === 'draft')
                        <form action="{{ route('eo.events.submit', $event->id) }}"
                              method="POST"
                              class="my-event-submit-form"
                              onsubmit="return confirm('Ajukan event ini untuk diverifikasi admin?')">
                            @csrf
                            <button type="submit"
                                    class="my-events-btn my-events-btn-primary">
                                Ajukan Verifikasi
                            </button>
                        </form>
                    @endif
                </div>

            </article>
        @empty
            <div class="my-events-empty">
                <div class="my-events-empty-icon">+</div>
                <h2>Belum Ada Event</h2>
                <p>Event yang kamu buat akan muncul di halaman ini.</p>

                <a href="{{ route('eo.events.create') }}"
                   class="my-events-btn my-events-btn-primary">
                    Buat Event Pertama
                </a>
            </div>
        @endforelse
    </div>

</div>
@endsection