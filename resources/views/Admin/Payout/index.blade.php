@extends('layouts.admin')

@section('title', 'Audit & Approval Payout - Admin M!VENT')
@section('page-title', 'Audit & Approval Payout')
@section('page-subtitle', 'Verifikasi pengajuan pencairan dana dan transfer ke Event Organizer.')

@section('content')
    <div
        style="background: #fff; padding: 28px; border-radius: 12px; border: 1px solid #e4e4e7; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
        <div style="margin-bottom: 24px; border-bottom: 1px solid #f0f0f2; padding-bottom: 16px;">
            <h1 style="font-size: 22px; margin-bottom: 4px; color: #111;">Daftar Pengajuan Payout EO</h1>
            <p style="color: #666; font-size: 14px;">Audit kelengkapan data, validasi saldo transaksi, dan proses transfer
                dana EO.</p>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 14px; text-align: left;">
                <thead>
                    <tr style="background-color: #f8f8f9; border-bottom: 2px solid #e4e4e7; color: #444;">
                        <th style="padding: 14px;">Kode & Waktu Pengajuan</th>
                        <th style="padding: 14px;">Event Organizer</th>
                        <th style="padding: 14px;">Rekening Tujuan</th>
                        <th style="padding: 14px;">Rincian Dana</th>
                        <th style="padding: 14px;">Status</th>
                        <th style="padding: 14px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payouts as $item)
                        <tr style="border-bottom: 1px solid #f0f0f2;">
                            <td style="padding: 14px;">
                                <strong>{{ $item->payout_code }}</strong><br>
                                <span style="font-size: 12px; color: #777;">
                                    {{ \Carbon\Carbon::parse($item->requested_at ?? $item->created_at)->format('d M Y, H:i') }}
                                </span>
                            </td>
                            <td style="padding: 14px;">
                                <strong>{{ $item->eo_name }}</strong><br>
                                <span style="font-size: 12px; color: #777;">{{ $item->eo_email }}</span>
                            </td>
                            <td style="padding: 14px;">
                                <strong>{{ strtoupper($item->bank_name) }}</strong> - {{ $item->bank_account_number }}<br>
                                <span style="font-size: 12px; color: #555;">a.n. {{ $item->bank_account_holder }}</span>
                            </td>
                            <td style="padding: 14px;">
                                <div style="font-weight: bold; color: #111;">
                                    Bersih: Rp {{ number_format((float) $item->net_amount, 0, ',', '.') }}
                                </div>
                                <div style="font-size: 11px; color: #777;">
                                    Kotor: Rp {{ number_format((float) $item->gross_amount, 0, ',', '.') }} | Fee: Rp
                                    {{ number_format((float) $item->commission_fee, 0, ',', '.') }}
                                </div>
                            </td>
                            <td style="padding: 14px;">
                                @if ($item->status === 'pending')
                                    <span
                                        style="background: #fef3c7; color: #92400e; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold;">Menunggu
                                        Audit</span>
                                @elseif ($item->status === 'completed' || $item->status === 'approved')
                                    <span
                                        style="background: #dcfce7; color: #166534; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold;">Berhasil
                                        Ditransfer</span>
                                    @if ($item->proof_of_transfer)
                                        <div style="font-size: 11px; color: #666; margin-top: 4px;">Ref:
                                            {{ $item->proof_of_transfer }}</div>
                                    @endif
                                @elseif ($item->status === 'rejected')
                                    <span
                                        style="background: #fee2e2; color: #991b1b; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold;">Ditolak</span>
                                    @if ($item->rejection_reason)
                                        <div style="font-size: 11px; color: #b91c1c; margin-top: 4px; max-width: 220px;">
                                            Alasan: {{ $item->rejection_reason }}</div>
                                    @endif
                                @endif
                            </td>
                            <td style="padding: 14px; text-align: center;">
                                <a href="{{ route('admin.payout.show', $item->id) }}"
                                    style="display: inline-block; padding: 8px 16px; background: #111; color: #fff; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 600;">
                                    {{ $item->status === 'pending' ? 'Periksa & Audit' : 'Lihat Detail' }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 40px; text-align: center; color: #777;">
                                Belum ada pengajuan payout dari Event Organizer.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
