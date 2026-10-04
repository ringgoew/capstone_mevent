@extends('layouts.main')

@section('content')


<!-- HERO -->
<section class="hero">
<h1>
    Temukan Event Menarik di Sekitarmu
</h1>

<p>
    Cari dan beli tiket event favoritmu dengan mudah.
</p>

<input
    type="text"
    class="search"
    placeholder="Cari event..."
>
</section>

<!-- EVENT -->
<section class="event-section">

<h2>Event Terbaru</h2>

<div class="event-container">

    @foreach ($events as $event)

        <div class="event-card">

            <div class="event-image">
                <img
                    src="{{ asset('assets/img/events/' . $event['foto']) }}"
                    alt="{{ $event['nama'] }}"
                >
            </div>

            <div class="event-content">

                <h3>
                    {{ $event['nama'] }}
                </h3>

                <p>
                    📅 {{ $event['tanggal'] }}
                </p>

                <p>
                    📍 {{ $event['location'] }}
                </p>

                <p class="price">
                    {{ $event['harga'] }}
                </p>

                <a href="#" class="btn-detail">
                    Lihat Detail
                </a>

            </div>

        </div>

    @endforeach

</div>


@endsection