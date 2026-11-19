@extends('layouts.eo')

@section('title', 'Kelola Payout & Pencairan Dana')

@section('content')
    <div style="max-width: 1000px; margin: 20px auto; padding: 20px;">

        <h2 style="margin-bottom: 5px; color: #1e293b;">Pencairan Dana (Payout)</h2>
        <p style="color: #64748b; margin-bottom: 25px; font-size: 14px;">
            Ajukan pencairan hasil penjualan tiket per event Anda ke rekening bank pribadi atau perusahaan.
        </p>

        @if (session('success'))
            <div
                style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #bbf7d0;">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div
                style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #fecaca;">
                ❌ {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div
                style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #fecaca;">
                @foreach ($errors->all() as $err)
                    <div>❌ {{ $err }}</div>
                @endforeach
            </div>
        @endif

        <!-- KARTU INFORMASI SALDO -->
        <div
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <div
                style="background: #fff; padding: 20px; border-radius: 10px; border: 1px solid #e2e8f0; box-shadow: 0 2px 5px rgba(0,0,0,0.02);">
                <p style="font-size: 13px; color: #64748b; margin: 0 0 5px 0;">Total Pendapatan Kotor (Semua Event)</p>
                <h3 style="font-size: 24px; color: #0f172a; margin: 0;">Rp
                    {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</h3>
            </div>
            <div
                style="background: #fff; padding: 20px; border-radius: 10px; border: 1px solid #e2e8f0; box-shadow: 0 2px 5px rgba(0,0,0,0.02);">
                <p style="font-size: 13px; color: #64748b; margin: 0 0 5px 0;">Total Saldo Tersedia untuk Dicairkan</p>
                <h3 style="font-size: 24px; color: #16a34a; margin: 0;">Rp
                    {{ number_format($availableBalance ?? 0, 0, ',', '.') }}</h3>
            </div>
        </div>

        <!-- FORM PENGAJUAN PAYOUT PER EVENT -->
        <div
            style="background: #fff; padding: 25px; border-radius: 10px; border: 1px solid #e2e8f0; margin-bottom: 35px; box-shadow: 0 2px 5px rgba(0,0,0,0.02);">
            <h3 style="margin-top: 0; font-size: 18px; color: #1e293b; margin-bottom: 15px;">➕ Buat Pengajuan Payout Baru
            </h3>

            <form action="{{ route('eo.payout.store') }}" method="POST">
                @csrf

                <!-- PILIH EVENT -->
                <div style="margin-bottom: 15px;">
                    <label
                        style="display: block; font-size: 13px; font-weight: bold; color: #475569; margin-bottom: 5px;">Pilih
                        Event</label>
                    <select name="event_id" id="eventSelect" required onchange="updateSaldoEvent(this)"
                        style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box; background: #fff;">
                        <option value="" data-saldo="0" disabled selected>-- Pilih Event yang Akan Dicairkan Dananya
                            --</option>
                        @foreach ($events as $ev)
                            <option value="{{ $ev->id }}" data-saldo="{{ $ev->available_balance }}"
                                {{ old('event_id') == $ev->id ? 'selected' : '' }}
                                {{ $ev->available_balance <= 0 ? 'disabled' : '' }}>
                                {{ $ev->event_name }} — (Saldo Tersedia: Rp
                                {{ number_format($ev->available_balance, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                    <small id="saldoEventInfo"
                        style="display: block; margin-top: 6px; color: #16a34a; font-weight: 600;"></small>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 15px;">
                    <div>
                        <label
                            style="display: block; font-size: 13px; font-weight: bold; color: #475569; margin-bottom: 5px;">Nominal
                            Payout (Rp)</label>
                        <input type="number" name="amount" id="amountInput" value="{{ old('amount') }}"
                            placeholder="Contoh: 150000" required
                            style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box;">
                    </div>
                    <div>
                        <label
                            style="display: block; font-size: 13px; font-weight: bold; color: #475569; margin-bottom: 5px;">Nama
                            Bank (BCA, Mandiri, dll)</label>
                        <input type="text" name="bank_name" value="{{ old('bank_name') }}" placeholder="Contoh: BCA"
                            required
                            style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label
                            style="display: block; font-size: 13px; font-weight: bold; color: #475569; margin-bottom: 5px;">Nomor
                            Rekening Tujuan</label>
                        <input type="text" name="bank_account_number" value="{{ old('bank_account_number') }}"
                            placeholder="Contoh: 1234567890" required
                            style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box;">
                    </div>
                    <div>
                        <label
                            style="display: block; font-size: 13px; font-weight: bold; color: #475569; margin-bottom: 5px;">Nama
                            Pemilik Rekening (Sesuai Buku Tabungan)</label>
                        <input type="text" name="bank_account_holder" value="{{ old('bank_account_holder') }}"
                            placeholder="Contoh: Budi Santoso" required
                            style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box;">
                    </div>
                </div>

                <button type="submit"
                    style="background: #2563eb; color: #fff; border: none; padding: 12px 20px; font-weight: bold; border-radius: 6px; cursor: pointer;">
                    Kirim Pengajuan Payout
                </button>
            </form>
        </div>

        <!-- TABEL RIWAYAT PAYOUT -->
        <div
            style="background: #fff; padding: 25px; border-radius: 10px; border: 1px solid #e2e8f0; box-shadow: 0 2px 5px rgba(0,0,0,0.02);">
            <h3 style="margin-top: 0; font-size: 18px; color: #1e293b; margin-bottom: 15px;">📋 Riwayat Pengajuan Payout
            </h3>

            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; color: #475569;">
                            <th style="padding: 12px;">Kode</th>
                            <th style="padding: 12px;">Event</th>
                            <th style="padding: 12px;">Tanggal</th>
                            <th style="padding: 12px;">Jumlah</th>
                            <th style="padding: 12px;">Tujuan Bank</th>
                            <th style="padding: 12px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payouts as $payout)
                            <tr style="border-bottom: 1px solid #e2e8f0;">
                                <td style="padding: 12px; font-family: monospace; font-weight: bold;">
                                    {{ $payout->payout_code }}</td>
                                <td style="padding: 12px; font-weight: 600; color: #1e293b;">
                                    {{ $payout->event_name ?? '-' }}</td>
                                <td style="padding: 12px;">{{ date('d M Y, H:i', strtotime($payout->created_at)) }}</td>
                                <td style="padding: 12px; font-weight: bold;">Rp
                                    {{ number_format($payout->net_amount, 0, ',', '.') }}</td>
                                <td style="padding: 12px;">{{ $payout->bank_name }} -
                                    {{ $payout->bank_account_number }}<br><small style="color: #64748b;">a.n
                                        {{ $payout->bank_account_holder }}</small></td>
                                <td style="padding: 12px;">
                                    @if ($payout->status == 'pending')
                                        <span
                                            style="background: #fef9c3; color: #854d0e; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: bold;">Pending</span>
                                    @elseif(in_array($payout->status, ['completed', 'approved', 'transferred']))
                                        <span
                                            style="background: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: bold;">Berhasil
                                            / Ditransfer</span>
                                    @else
                                        <span
                                            style="background: #fee2e2; color: #991b1b; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: bold;">Ditolak</span>
                                        @if (!empty($payout->rejection_reason))
                                            <div style="font-size: 11px; color: #b91c1c; margin-top: 4px;">Alasan:
                                                {{ $payout->rejection_reason }}</div>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 20px; color: #64748b;">Belum ada
                                    riwayat pengajuan payout.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
        function updateSaldoEvent(selectEl) {
            const selectedOption = selectEl.options[selectEl.selectedIndex];
            const saldo = parseFloat(selectedOption.getAttribute('data-saldo')) || 0;
            const amountInput = document.getElementById('amountInput');
            const infoEl = document.getElementById('saldoEventInfo');

            if (saldo > 0) {
                amountInput.max = saldo;
                amountInput.value = saldo;
                infoEl.textContent = 'Maksimal pencairan untuk event ini: Rp ' + saldo.toLocaleString('id-ID');
            } else {
                amountInput.removeAttribute('max');
                infoEl.textContent = '';
            }
        }
    </script>
@endsection
