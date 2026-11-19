@extends('layouts.admin')

@section('title', 'Detail Audit Payout ' . $payout->payout_code)
@section('page-title', 'Audit & Approval Payout')
@section('page-subtitle', 'Verifikasi pengajuan pencairan dana dan transfer ke Event Organizer.')

@section('content')
    <div style="margin-bottom: 18px;">
        <a href="{{ route('admin.payout.index') }}"
            style="display: inline-block; padding: 8px 16px; background: #fff; border: 1px solid #ddd; border-radius: 6px; color: #111; text-decoration: none; font-size: 13px; font-weight: 600;">
            ← Kembali ke Daftar Payout
        </a>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">

        <!-- KARTU 1: IDENTITAS EO, REKENING & RINCIAN PAYOUT -->
        <div style="background: #fff; padding: 24px; border-radius: 12px; border: 1px solid #e4e4e7;">
            <h2 style="font-size: 17px; margin-bottom: 16px; padding-bottom: 10px; border-bottom: 1px solid #f0f0f2;">
                1. Identitas EO & Rekening Tujuan
            </h2>
            <table style="width: 100%; font-size: 14px; line-height: 2;">
                <tr>
                    <td style="color: #666; width: 44%;">Kode Payout</td>
                    <td><strong>{{ $payout->payout_code }}</strong></td>
                </tr>
                <tr>
                    <td style="color: #666;">Nama EO</td>
                    <td><strong>{{ $payout->eo_name }}</strong> ({{ $payout->eo_email }})</td>
                </tr>
                <tr>
                    <td style="color: #666;">Event Terkait</td>
                    <td><strong>{{ $payout->event_name }}</strong></td>
                </tr>
                <tr>
                    <td style="color: #666;">Bank Tujuan</td>
                    <td><strong>{{ strtoupper($payout->bank_name) }}</strong></td>
                </tr>
                <tr>
                    <td style="color: #666;">Nomor Rekening</td>
                    <td><strong style="font-size: 15px; letter-spacing: 0.5px;">{{ $payout->bank_account_number }}</strong>
                    </td>
                </tr>
                <tr>
                    <td style="color: #666;">Pemilik Rekening</td>
                    <td><strong>{{ $payout->bank_account_holder }}</strong></td>
                </tr>
                <tr>
                    <td style="color: #666;">Pendapatan Kotor (Gross)</td>
                    <td>Rp {{ number_format((float) $payout->gross_amount, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="color: #666;">Komisi Platform (Fee)</td>
                    <td style="color: #b91c1c;">- Rp {{ number_format((float) $payout->commission_fee, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="color: #666;"><strong>Dana Bersih Ditransfer</strong></td>
                    <td style="font-size: 18px; font-weight: 800; color: #15803d;">
                        Rp {{ number_format((float) $payout->net_amount, 0, ',', '.') }}
                    </td>
                </tr>
            </table>
        </div>

        <!-- KARTU 2: HASIL PEMERIKSAAN SALDO & KEPUTUSAN AUDIT -->
        <div style="background: #fff; padding: 24px; border-radius: 12px; border: 1px solid #e4e4e7;">
            <h2 style="font-size: 17px; margin-bottom: 16px; padding-bottom: 10px; border-bottom: 1px solid #f0f0f2;">
                2. Hasil Pemeriksaan Sistem (Audit Saldo)
            </h2>

            <div
                style="background: #f8f8f9; padding: 14px; border-radius: 8px; margin-bottom: 16px; font-size: 14px; line-height: 1.9;">
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #555;">Total Pemasukan Tiket Lunas (Event):</span>
                    <strong>Rp {{ number_format($audit['total_pemasukan_event'], 0, ',', '.') }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #555;">Payout Event Sudah Dicairkan:</span>
                    <span style="color: #b91c1c;">- Rp
                        {{ number_format($audit['total_sudah_dicairkan'], 0, ',', '.') }}</span>
                </div>
                <hr style="border: none; border-top: 1px dashed #ccc; margin: 8px 0;">
                <div style="display: flex; justify-content: space-between; font-size: 15px;">
                    <span><strong>Saldo Kotor Event Tersedia:</strong></span>
                    <strong style="color: #15803d;">Rp
                        {{ number_format($audit['saldo_kotor_tersedia'], 0, ',', '.') }}</strong>
                </div>
            </div>

            <!-- Status Validasi Otomatis -->
            @if ($isAuditValid)
                <div
                    style="background: #e6f4ea; color: #137333; padding: 12px; border-radius: 8px; font-size: 13px; margin-bottom: 18px; border: 1px solid #ceead6;">
                    ✅ <strong>Hasil Audit Valid:</strong> Saldo transaksi event mencukupi (Rp
                    {{ number_format((float) $payout->gross_amount, 0, ',', '.') }}) dan perhitungan potongan komisi
                    sesuai.
                </div>
            @else
                <div
                    style="background: #fce8e6; color: #c5221f; padding: 12px; border-radius: 8px; font-size: 13px; margin-bottom: 18px; border: 1px solid #fad2cf;">
                    ⚠️ <strong>Peringatan Audit:</strong>
                    @if (!$isSaldoCukup)
                        Nominal pengajuan melebihi saldo transaksi lunas pada event ini!
                    @endif
                    @if (!$isHitunganValid)
                        Selisih perhitungan gross_amount dikurangi commission_fee tidak sesuai dengan net_amount!
                    @endif
                </div>
            @endif

            <!-- FORM KEPUTUSAN ADMIN -->
            @if ($payout->status === 'pending')
                <form action="{{ route('admin.payout.approve', $payout->id) }}" method="POST"
                    style="margin-bottom: 16px;">
                    @csrf
                    <button type="submit"
                        onclick="return confirm('Setujui pengajuan {{ $payout->payout_code }} dan teruskan perintah transfer dana bersih Rp {{ number_format((float) $payout->net_amount, 0, ',', '.') }} ke Payment Gateway?');"
                        {{ !$isAuditValid ? 'disabled' : '' }}
                        style="width: 100%; padding: 12px; background: #16a34a; color: #fff; border: none; border-radius: 6px; font-weight: bold; font-size: 14px; {{ !$isAuditValid ? 'opacity: 0.5; cursor: not-allowed;' : 'cursor: pointer;' }}">
                        ✓ Setujui & Proses Transfer Dana
                    </button>
                </form>

                <hr style="border: none; border-top: 1px solid #eee; margin: 16px 0;">

                <form action="{{ route('admin.payout.reject', $payout->id) }}" method="POST">
                    @csrf
                    <label
                        style="display: block; font-size: 13px; font-weight: bold; color: #b91c1c; margin-bottom: 6px;">Alasan
                        Penolakan (Wajib jika ditolak)</label>
                    <textarea name="rejection_reason" rows="2" required
                        placeholder="Tuliskan alasan penolakan (misal: Nama pemilik rekening tidak sesuai data KYC / Saldo transaksi tidak sesuai)..."
                        style="width: 100%; padding: 10px; border: 1px solid #fca5a5; border-radius: 6px; margin-bottom: 10px; font-size: 13px;"></textarea>

                    <button type="submit" onclick="return confirm('Tolak pengajuan payout {{ $payout->payout_code }}?');"
                        style="width: 100%; padding: 10px; background: #dc2626; color: #fff; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 13px;">
                        ✕ Tolak Pengajuan Payout
                    </button>
                </form>
            @else
                <!-- Tampilan Riwayat Audit Jika Sudah Diproses -->
                <div style="background: #f4f4f5; padding: 14px; border-radius: 8px; font-size: 13px; line-height: 1.8;">
                    <strong>Status Akhir:</strong> {{ strtoupper($payout->status) }}<br>
                    <strong>Diproses Oleh Admin:</strong>
                    {{ $payout->processor_name ?? 'Admin #' . $payout->processed_by }}<br>
                    <strong>Waktu Diproses:</strong>
                    {{ $payout->processed_at ? \Carbon\Carbon::parse($payout->processed_at)->format('d M Y, H:i') : '-' }}<br>
                    @if ($payout->status === 'completed' || $payout->status === 'approved')
                        <strong>Bukti / Referensi Transfer:</strong> {{ $payout->proof_of_transfer ?? '-' }}
                    @elseif ($payout->status === 'rejected')
                        <strong style="color: #b91c1c;">Alasan Penolakan:</strong> {{ $payout->rejection_reason }}
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- KARTU 3: RIWAYAT TRANSAKSI TIKET TERKAIT -->
    <div style="background: #fff; padding: 24px; border-radius: 12px; border: 1px solid #e4e4e7;">
        <h2 style="font-size: 17px; margin-bottom: 16px; padding-bottom: 10px; border-bottom: 1px solid #f0f0f2;">
            3. Riwayat Transaksi Tiket Lunas ({{ $payout->event_name }})
        </h2>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 13px; text-align: left;">
                <thead>
                    <tr style="background: #f8f8f9; border-bottom: 1px solid #e4e4e7;">
                        <th style="padding: 12px;">Kode Order</th>
                        <th style="padding: 12px;">Status Pembayaran</th>
                        <th style="padding: 12px;">Tanggal Transaksi</th>
                        <th style="padding: 12px; text-align: right;">Nominal Masuk</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($audit['riwayat_transaksi'] as $trx)
                        <tr style="border-bottom: 1px solid #f0f0f2;">
                            <td style="padding: 12px; font-weight: bold;">{{ $trx->order_code ?? 'ORD-' . $trx->id }}</td>
                            <td style="padding: 12px;">
                                <span
                                    style="background: #dcfce7; color: #166534; padding: 3px 10px; border-radius: 12px; font-size: 11px; font-weight: bold;">
                                    {{ strtoupper($trx->status) }}
                                </span>
                            </td>
                            <td style="padding: 12px; color: #666;">
                                {{ \Carbon\Carbon::parse($trx->created_at)->format('d M Y, H:i') }}</td>
                            <td style="padding: 12px; text-align: right; font-weight: 600;">Rp
                                {{ number_format((float) $trx->total_amount, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="padding: 24px; text-align: center; color: #777;">
                                Tidak ditemukan riwayat transaksi tiket yang berstatus lunas untuk event ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
