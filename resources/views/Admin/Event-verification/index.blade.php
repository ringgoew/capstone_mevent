@extends('layouts.admin')

@section('title', 'Pemeriksaan Pengajuan Event — M!VENT Admin')

@section('content')

<div class="header">
    <h1>Pemeriksaan Pengajuan Event</h1>
    <p>Tinjau dan verifikasi kelayakan event yang diajukan oleh Event Organizer sebelum dipublikasikan ke publik.</p>
</div>

<div class="card">
    <h3 style="font-size: 17px; font-weight: 700; color: #0F172A; margin-bottom: 16px;">Daftar Event Menunggu Review</h3>

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
                        <td style="font-weight: 600; color: #0F172A;">{{ $event->title }}</td>
                        <td>{{ $event->eo_name }}</td>
                        <td><span class="badge" style="background: #F0F9FF; color: #0369A1;">{{ $event->category_name }}</span></td>
                        <td>{{ \Carbon\Carbon::parse($event->start_time)->translatedFormat('d M Y, H:i') }}</td>
                        <td>
                            <span class="badge pending">
                                PENDING REVIEW
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.event-verification.show', $event->id) }}" class="btn btn-dark" style="font-size: 13px; padding: 7px 14px;">
                                Periksa Kelayakan →
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 36px; color: #94A3B8;">
                            Tidak ada pengajuan event yang sedang menunggu review.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
