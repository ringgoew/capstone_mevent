@extends('admin.event-verification.layout')

@section('title', 'Detail Pemeriksaan Event')

@section('content')

    <a href="{{ route('admin.event-verification.index') }}" class="btn btn-gray">
        &larr; Kembali ke Daftar Event
    </a>

    <h1 style="margin-top:24px">Pemeriksaan Detail Event</h1>

    <div class="card">
        <h2>{{ $event->title }}</h2>

        <p>
            <span class="badge {{ $event->status }}">
                {{ strtoupper($event->status) }}
            </span>
        </p>

        <div class="field">
            <label>Event Organizer</label>
            <p>{{ $event->eo_name }}</p>
        </div>

        <div class="field">
            <label>Email EO</label>
            <p>{{ $event->eo_email }}</p>
        </div>

        <div class="field">
            <label>Kategori</label>
            <p>{{ $event->category_name }}</p>
        </div>

        <div class="field">
            <label>Deskripsi</label>
            <p>{{ $event->description }}</p>
        </div>

        <div class="field">
            <label>Lokasi</label>
            <p>
                {{ $event->venue_name }},
                {{ $event->city }}.
                {{ $event->address }}
            </p>
        </div>

        <div class="field">
            <label>Waktu Mulai</label>
            <p>{{ $event->start_time }}</p>
        </div>

        <div class="field">
            <label>Waktu Selesai</label>
            <p>{{ $event->end_time }}</p>
        </div>

        @if ($event->banner_image)
            <div class="field">
                <label>Path Banner</label>
                <p>{{ $event->banner_image }}</p>
            </div>
        @endif

        @if ($event->rejection_reason)
            <div class="field">
                <label>Catatan Penolakan</label>
                <p>{{ $event->rejection_reason }}</p>
            </div>
        @endif
    </div>

    <div class="card">
        <h2>Detail Jenis Tiket dan Kuota</h2>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nama Tiket</th>
                        <th>Harga</th>
                        <th>Total Kuota</th>
                        <th>Reserved</th>
                        <th>Terjual</th>
                        <th>Maks./Order</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($tickets as $ticket)
                        <tr>
                            <td>{{ $ticket->name }}</td>
                            <td>
                                Rp {{ number_format($ticket->price, 0, ',', '.') }}
                            </td>
                            <td>{{ $ticket->quota }}</td>
                            <td>{{ $ticket->reserved_quota }}</td>
                            <td>{{ $ticket->sold_quota }}</td>
                            <td>{{ $ticket->max_per_order }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                Belum ada jenis tiket. Periksa kembali pengajuan EO.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($event->status === 'pending')
        <div class="card">
            <h2>Keputusan Admin</h2>

            <p>
                Pastikan informasi acara, lokasi, waktu, dan pengaturan tiket
                layak sebelum event dipublikasikan.
            </p>

            <form action="{{ route('admin.event-verification.publish', $event->id) }}" method="POST"
                style="margin-top:20px" onsubmit="return confirm('Publikasikan event ini dan buka penjualan tiket?')">
                @csrf

                <button type="submit" class="btn btn-green">
                    Publish Event
                </button>
            </form>

            <hr style="border:0;border-top:1px solid #eee;margin:25px 0">

            <h3>Tolak Pengajuan Event</h3>

            <form action="{{ route('admin.event-verification.reject', $event->id) }}" method="POST"
                style="margin-top:15px" onsubmit="return confirm('Yakin ingin menolak event ini?')">
                @csrf

                <div class="field">
                    <label>Alasan Penolakan</label>

                    <textarea name="rejection_reason" maxlength="2000" placeholder="Jelaskan alasan event tidak disetujui." required>{{ old('rejection_reason') }}</textarea>
                </div>

                <button type="submit" class="btn btn-red">
                    Reject Event
                </button>
            </form>
        </div>
    @else
        <div class="card">
            Event ini sudah diproses. Keputusan tidak dapat diubah melalui
            halaman ini.
        </div>
    @endif

@endsection
