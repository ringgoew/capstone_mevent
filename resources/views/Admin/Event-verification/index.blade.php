@extends('admin.event-verification.layout')

@section('title', 'Pemeriksaan Pengajuan Event')

@section('content')

<h1>Pemeriksaan Pengajuan Event</h1>

<p>
    Periksa kelayakan event yang diajukan EO sebelum dipublikasikan.
</p>

<div class="card">
    <h3>Daftar Event Menunggu Review</h3>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nama Event</th>
                    <th>Event Organizer</th>
                    <th>Kategori</th>
                    <th>Waktu Mulai</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($events as $event)
                    <tr>
                        <td>{{ $event->title }}</td>
                        <td>{{ $event->eo_name }}</td>
                        <td>{{ $event->category_name }}</td>
                        <td>{{ $event->start_time }}</td>
                        <td>
                            <span class="badge pending">
                                PENDING REVIEW
                            </span>
                        </td>
                        <td>
                            <a
                                href="{{ route('admin.event-verification.show', $event->id) }}"
                                class="btn btn-dark"
                            >
                                Periksa
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            Tidak ada pengajuan event yang menunggu review.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
