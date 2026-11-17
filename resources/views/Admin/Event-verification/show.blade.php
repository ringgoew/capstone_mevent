@extends('admin.event-verification.layout')

@section('title', 'Detail Pemeriksaan Event — ' . $event->title)

@section('content')

    <a href="{{ route('admin.event-verification.index') }}" class="btn btn-gray" style="margin-bottom: 20px;">
        &larr; Kembali ke Daftar Event
    </a>

    <div class="header">
        <h1>Pemeriksaan Kelayakan Event</h1>
        <p>Tinjau informasi detail, kepatuhan lokasi, dan kuota tiket sebelum mempublikasikan event ke publik.</p>
    </div>

    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
            <h2 style="font-size: 22px; font-weight: 800; color: #0F172A; margin: 0;">{{ $event->title }}</h2>

            <span class="badge {{ $event->status }}">
                {{ strtoupper($event->status) }}
            </span>
        </div>

        @if ($event->banner_image)
            <div class="field" style="margin-bottom: 24px;">
                <label>Banner Event</label>
                <img src="{{ asset('storage/' . $event->banner_image) }}" alt="{{ $event->title }}" style="width: 100%; max-height: 300px; border-radius: 12px; object-fit: cover; border: 1.5px solid #E2E8F0; margin-top: 8px;">
            </div>
        @endif

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 18px;">
            <div class="field">
                <label>Event Organizer</label>
                <p style="font-weight: 600; color: #0F172A;">{{ $event->eo_name }}</p>
            </div>

            <div class="field">
                <label>Email Penyelenggara</label>
                <p>{{ $event->eo_email }}</p>
            </div>

            <div class="field">
                <label>Kategori Acara</label>
                <p><span class="badge" style="background: #F0F9FF; color: #0369A1;">{{ $event->category_name }}</span></p>
            </div>

            <div class="field">
                <label>Waktu Pelaksanaan</label>
                <p>{{ \Carbon\Carbon::parse($event->start_time)->translatedFormat('d M Y, H:i') }} &mdash; {{ \Carbon\Carbon::parse($event->end_time)->translatedFormat('d M Y, H:i') }} WIB</p>
            </div>
        </div>

        <div class="field" style="margin-top: 8px;">
            <label>Lokasi & Venue</label>
            <p style="font-weight: 600; color: #0F172A;">{{ $event->venue_name }}, {{ $event->city }}</p>
            <p style="color: #64748B; font-size: 13px; margin-top: 2px;">{{ $event->address }}</p>
        </div>

        <div class="field" style="margin-top: 14px;">
            <label>Deskripsi Event</label>
            <p style="line-height: 1.7; background: #F8FAFC; padding: 14px; border-radius: 8px; border: 1px solid #E2E8F0;">
                {{ $event->description }}
            </p>
        </div>

        @if ($event->rejection_reason)
            <div class="field" style="margin-top: 16px; background: #FEF2F2; padding: 14px; border-radius: 8px; border: 1px solid #FECACA;">
                <label style="color: #991B1B;">Catatan Penolakan Sebelumnya</label>
                <p style="color: #7F1D1D;">{{ $event->rejection_reason }}</p>
            </div>
        @endif
    </div>

    <div class="card">
        <h2 style="font-size: 18px; font-weight: 700; color: #0F172A; margin-bottom: 16px;">Detail Jenis Tiket dan Kuota</h2>

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
                            <td style="font-weight: 600; color: #0F172A;">{{ $ticket->name }}</td>
                            <td style="font-weight: 700; color: #0284C7;">
                                @if($ticket->price > 0)
                                    Rp {{ number_format($ticket->price, 0, ',', '.') }}
                                @else
                                    Gratis
                                @endif
                            </td>
                            <td>{{ $ticket->quota }}</td>
                            <td>{{ $ticket->reserved_quota ?? 0 }}</td>
                            <td>{{ $ticket->sold_quota ?? 0 }}</td>
                            <td>{{ $ticket->max_per_order }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 30px; color: #94A3B8;">
                                Belum ada jenis tiket. Periksa kembali pengajuan EO.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($event->status === 'pending')
        <div class="card" style="border: 1.5px solid #BAE6FD; background: #F0F9FF;">
            <h2 style="font-size: 18px; font-weight: 800; color: #0F172A; margin-bottom: 8px;">Keputusan Verifikasi Event</h2>

            <p style="color: #64748B; font-size: 14px; margin-bottom: 20px;">
                Pastikan informasi acara, kelayakan lokasi, waktu pelaksanaan, dan pengaturan kuota tiket sudah sesuai ketentuan sebelum event dipublikasikan.
            </p>

            <form action="{{ route('admin.event-verification.publish', $event->id) }}" method="POST"
                onsubmit="return confirm('Publikasikan event ini sekarang? Event akan langsung muncul di katalog publik dan tiket dapat dibeli.')">
                @csrf

                <button type="submit" class="btn btn-green">
                    ✓ Setujui & Publikasikan Event
                </button>
            </form>

            <hr style="border: 0; border-top: 1px solid #BAE6FD; margin: 24px 0;">

            <h3 style="font-size: 16px; font-weight: 700; color: #991B1B; margin-bottom: 12px;">Tolak Pengajuan Event</h3>

            <form action="{{ route('admin.event-verification.reject', $event->id) }}" method="POST"
                onsubmit="return confirm('Apakah Anda yakin ingin menolak pengajuan event ini? Penyelenggara akan menerima catatan alasan.')">
                @csrf

                <div class="field">
                    <label style="color: #991B1B;">Alasan Penolakan <span style="color: #EF4444;">*</span></label>
                    <textarea name="rejection_reason" maxlength="2000" placeholder="Tuliskan catatan detail mengapa event belum disetujui agar EO dapat memperbaikinya..." required>{{ old('rejection_reason') }}</textarea>
                </div>

                <button type="submit" class="btn btn-red">
                    ✕ Tolak dengan Catatan
                </button>
            </form>
        </div>
    @else
        <div class="card" style="display: flex; align-items: center; gap: 12px;">
            <span style="font-size: 14px; color: #64748B;">Status pengajuan ini:</span>
            <span class="badge {{ $event->status }}">
                {{ strtoupper($event->status) }}
            </span>
        </div>
    @endif

@endsection
