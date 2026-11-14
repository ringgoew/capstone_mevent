@extends('layouts.eo')

@section('title', 'Laporan Penjualan - ' . $event->title)

@section('content')
<div style="padding: 10px 0;">
    <!-- HEADER LAPORAN -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
        <div>
            <h1 style="margin: 0; font-size: 24px;">Laporan Penjualan: {{ $event->title }}</h1>
            <p style="color: #64748b; margin-top: 5px; font-size: 14px;">
                📍 {{ $event->venue_name ?? 'Lokasi belum diatur' }} &nbsp;|&nbsp; 
                📅 {{ $event->start_time ? \Carbon\Carbon::parse($event->start_time)->translatedFormat('d F Y, H:i') : '-' }}
            </p>
        </div>

        <div style="display: flex; gap: 10px;">
            <a href="{{ route('eo.reports.index') }}" class="btn" style="background: #64748b; color: #fff; padding: 10px 16px; border-radius: 6px; text-decoration: none; font-size: 14px;">
                ← Kembali
            </a>
            <button onclick="window.print()" class="btn" style="background: #1e293b; color: #fff; padding: 10px 16px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">
                🖨️ Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 20px 0;">

    <!-- 1. RINGKASAN PENJUALAN (CARDS) -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div style="background: #fff; padding: 20px; border-radius: 10px; border: 1px solid #e2e8f0;">
            <p style="color: #64748b; font-size: 13px; margin: 0 0 8px 0; text-transform: uppercase; font-weight: 600;">Total Transaksi Sukses</p>
            <h2 style="color: #2563eb; margin: 0; font-size: 26px;">
                {{ $salesSummary->total_transactions ?? 0 }} 
                <span style="font-size: 14px; font-weight: normal; color: #64748b;">pesanan</span>
            </h2>
        </div>

        <div style="background: #fff; padding: 20px; border-radius: 10px; border: 1px solid #e2e8f0;">
            <p style="color: #64748b; font-size: 13px; margin: 0 0 8px 0; text-transform: uppercase; font-weight: 600;">Total Pendapatan (Gross)</p>
            <h2 style="color: #16a34a; margin: 0; font-size: 26px;">
                Rp {{ number_format($salesSummary->gross_revenue ?? 0, 0, ',', '.') }}
            </h2>
        </div>

        <div style="background: #fff; padding: 20px; border-radius: 10px; border: 1px solid #e2e8f0;">
            <p style="color: #64748b; font-size: 13px; margin: 0 0 8px 0; text-transform: uppercase; font-weight: 600;">Pendapatan Bersih (Net)</p>
            <h2 style="color: #0f172a; margin: 0; font-size: 26px;">
                Rp {{ number_format($salesSummary->net_revenue ?? 0, 0, ',', '.') }}
            </h2>
        </div>
    </div>

    <!-- 2. PERFORMA JENIS TIKET (TABEL) -->
    <div style="background: #fff; padding: 25px; border-radius: 10px; border: 1px solid #e2e8f0; margin-bottom: 30px; overflow-x: auto;">
        <h3 style="margin: 0 0 15px 0; font-size: 18px;">Performa Jenis Tiket</h3>
        
        <table style="width: 100%; border-collapse: collapse; text-align: left; min-width: 600px;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; color: #475569; font-size: 13px;">
                    <th style="padding: 12px;">Jenis Tiket</th>
                    <th style="padding: 12px;">Harga Satuan</th>
                    <th style="padding: 12px;">Total Kuota</th>
                    <th style="padding: 12px;">Terjual (Sold)</th>
                    <th style="padding: 12px;">Tersedia (Sisa)</th>
                    <th style="padding: 12px;">Pendapatan Kategori</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($ticketPerformances as $tp)
                <tr style="border-bottom: 1px solid #e2e8f0; font-size: 14px;">
                    <td style="padding: 12px; font-weight: 600;">{{ $tp->ticket_name }}</td>
                    <td style="padding: 12px;">Rp {{ number_format($tp->price, 0, ',', '.') }}</td>
                    <td style="padding: 12px;">{{ $tp->quota }}</td>
                    <td style="padding: 12px; color: #16a34a; font-weight: bold;">{{ $tp->sold_quota }}</td>
                    <td style="padding: 12px; color: #2563eb; font-weight: 600;">{{ $tp->available_quota }}</td>
                    <td style="padding: 12px; font-weight: bold;">Rp {{ number_format($tp->total_revenue_by_type, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 20px; text-align: center; color: #64748b;">Belum ada jenis tiket untuk event ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- 3. DAFTAR KEHADIRAN PESERTA / CHECK-IN LOG (HASIL SCAN PANITIA) -->
    <div style="background: #fff; padding: 25px; border-radius: 10px; border: 1px solid #e2e8f0; overflow-x: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h3 style="margin: 0; font-size: 18px;">Daftar Kehadiran Peserta (Check-In)</h3>
            <span style="font-size: 13px; color: #64748b;">
                Hadir: <strong>{{ collect($attendees ?? [])->where('check_in_status', 'used')->count() }}</strong> / {{ count($attendees ?? []) }} peserta
            </span>
        </div>
        
        <table style="width: 100%; border-collapse: collapse; text-align: left; min-width: 650px;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; color: #475569; font-size: 13px;">
                    <th style="padding: 12px;">Kode Tiket</th>
                    <th style="padding: 12px;">Nama Peserta</th>
                    <th style="padding: 12px;">Jenis Tiket</th>
                    <th style="padding: 12px;">Status Kehadiran</th>
                    <th style="padding: 12px;">Waktu Check-In</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($attendees ?? [] as $att)
                <tr style="border-bottom: 1px solid #e2e8f0; font-size: 14px;">
                    <td style="padding: 12px; font-family: monospace; font-weight: bold; color: #334155;">{{ $att->ticket_code }}</td>
                    <td style="padding: 12px;">
                        <div style="font-weight: 600;">{{ $att->attendee_name }}</div>
                        <div style="font-size: 12px; color: #64748b;">{{ $att->attendee_email }}</div>
                    </td>
                    <td style="padding: 12px;">{{ $att->ticket_type }}</td>
                    <td style="padding: 12px;">
                        @if ($att->check_in_status === 'used')
                            <span style="padding: 4px 10px; background: #dcfce7; color: #166534; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                ✅ Hadir
                            </span>
                        @else
                            <span style="padding: 4px 10px; background: #f1f5f9; color: #64748b; border-radius: 20px; font-size: 12px; font-weight: 500;">
                                ⏳ Belum Hadir
                            </span>
                        @endif
                    </td>
                    <td style="padding: 12px; font-size: 13px; color: #475569;">
                        {{ $att->checked_in_at ? \Carbon\Carbon::parse($att->checked_in_at)->format('d M Y, H:i:s') : '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 25px; text-align: center; color: #64748b;">Belum ada tiket yang terjual untuk event ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection