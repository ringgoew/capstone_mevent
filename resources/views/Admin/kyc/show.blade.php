@extends('layouts.admin')
@section('title', 'Detail Verifikasi KYC EO — M!VENT Admin')
@section('content')

<a href="{{ route('admin.kyc.index') }}" class="btn btn-gray" style="margin-bottom: 20px;">
    &larr; Kembali ke Daftar KYC
</a>

<div class="header">
    <h1>Pemeriksaan Dokumen KYC</h1>
    <p>Organisasi: <strong>{{ $profile->organization_name }}</strong> &bull; Penanggung Jawab: <strong>{{ $profile->user_name }}</strong></p>
</div>

{{-- Notifikasi Error / Success --}}
@if(session('error'))
<div class="alert error">
    {{ session('error') }}
</div>
@endif

{{-- Form Utama untuk Approve / Reject (Menyatukan validasi dokumen dan aksi) --}}
<form id="kycActionForm" method="POST" action="">
    @csrf

    {{-- CARD KTP --}}
    <div class="card" id="card-ktp" style="transition: all 0.3s ease;">
        <h2 style="font-size: 18px; font-weight: 700; color: #0F172A; margin-bottom: 16px;">Dokumen KTP</h2>
        <div class="field">
            <label>Nomor KTP</label>
            <p style="font-size: 16px; font-weight: 600; color: #0F172A;">{{ $profile->ktp_number }}</p>
        </div>
        <div class="field">
            <label>Foto/Scan KTP</label>
            @if ($profile->ktp_image)
            <a href="{{ route('admin.kyc.document', ['id' => $profile->id, 'type' => 'ktp']) }}" target="_blank" rel="noopener" class="btn btn-dark">
                📄 Buka Dokumen KTP
            </a>
            @else
            <p style="color: #94A3B8;">Dokumen KTP belum tersedia.</p>
            @endif
        </div>

        @if ($profile->verification_status === 'pending')
        <div class="validation-box" style="margin-top: 16px; padding: 12px 16px; background: #F8FAFC; border-radius: 8px; border: 1px solid #E2E8F0;">
            <strong style="display: block; margin-bottom: 8px; font-size: 13px;">Tandai Validitas Dokumen <span style="color: #EF4444;">*</span>:</strong>
            <label style="margin-right: 20px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; font-weight: 500;">
                <input type="radio" name="ktp_check" value="valid" required onchange="setIndicator('card-ktp', 'valid')">
                <span style="color: #166534; font-weight: 700;">✓ Valid</span>
            </label>
            <label style="cursor: pointer; display: inline-flex; align-items: center; gap: 6px; font-weight: 500;">
                <input type="radio" name="ktp_check" value="invalid" required onchange="setIndicator('card-ktp', 'invalid')">
                <span style="color: #991B1B; font-weight: 700;">✕ Tidak Valid</span>
            </label>
        </div>
        @endif
    </div>

    {{-- CARD NPWP --}}
    <div class="card" id="card-npwp" style="transition: all 0.3s ease;">
        <h2 style="font-size: 18px; font-weight: 700; color: #0F172A; margin-bottom: 16px;">Dokumen NPWP</h2>
        <div class="field">
            <label>Nomor NPWP</label>
            <p style="font-size: 16px; font-weight: 600; color: #0F172A;">{{ $profile->npwp_number ?: 'Tidak diisi' }}</p>
        </div>
        <div class="field">
            <label>Foto/Scan NPWP</label>
            @if ($profile->npwp_image)
            <a href="{{ route('admin.kyc.document', ['id' => $profile->id, 'type' => 'npwp']) }}" target="_blank" rel="noopener" class="btn btn-dark">
                📄 Buka Dokumen NPWP
            </a>
            @else
            <p style="color: #94A3B8;">Dokumen NPWP tidak tersedia.</p>
            @endif
        </div>

        @if ($profile->verification_status === 'pending')
        <div class="validation-box" style="margin-top: 16px; padding: 12px 16px; background: #F8FAFC; border-radius: 8px; border: 1px solid #E2E8F0;">
            <strong style="display: block; margin-bottom: 8px; font-size: 13px;">Tandai Validitas Dokumen <span style="color: #EF4444;">*</span>:</strong>
            <label style="margin-right: 20px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; font-weight: 500;">
                <input type="radio" name="npwp_check" value="valid" required onchange="setIndicator('card-npwp', 'valid')">
                <span style="color: #166534; font-weight: 700;">✓ Valid</span>
            </label>
            <label style="cursor: pointer; display: inline-flex; align-items: center; gap: 6px; font-weight: 500;">
                <input type="radio" name="npwp_check" value="invalid" required onchange="setIndicator('card-npwp', 'invalid')">
                <span style="color: #991B1B; font-weight: 700;">✕ Tidak Valid</span>
            </label>
        </div>
        @endif
    </div>

    {{-- CARD INFORMASI REKENING BANK --}}
    <div class="card">
        <h2 style="font-size: 18px; font-weight: 700; color: #0F172A; margin-bottom: 16px;">Informasi Rekening Bank</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
            <div class="field" style="margin-bottom: 0;">
                <label>Nama Bank</label>
                <p style="font-weight: 600; color: #0F172A;">{{ $profile->bank_name ?: '-' }}</p>
            </div>
            <div class="field" style="margin-bottom: 0;">
                <label>Nomor Rekening</label>
                <p style="font-weight: 600; color: #0F172A;">{{ $profile->bank_account_number ?: '-' }}</p>
            </div>
            <div class="field" style="margin-bottom: 0;">
                <label>Nama Pemilik Rekening</label>
                <p style="font-weight: 600; color: #0F172A;">{{ $profile->bank_account_holder ?: '-' }}</p>
            </div>
        </div>
    </div>

    {{-- TOMBOL AKSI & MODAL / FORM PENOLAKAN (Jika Status Pending) --}}
    @if ($profile->verification_status === 'pending')
    <div class="card" style="border: 1.5px solid #BAE6FD; background: #F0F9FF;">
        <h3 style="font-size: 17px; font-weight: 700; color: #0F172A; margin-bottom: 14px;">Keputusan Verifikasi KYC</h3>

        {{-- Input Alasan Penolakan (Hidden secara default, muncul jika klik Tolak) --}}
        <div id="rejectionSection" style="display: none; margin-bottom: 16px;">
            <label for="rejection_reason" style="font-weight: 700; color: #991B1B; margin-bottom: 6px; display: block;">
                Alasan Penolakan <span style="color: #EF4444;">*</span>:
            </label>
            <textarea name="rejection_reason" id="rejection_reason" rows="4" placeholder="Tuliskan catatan detail alasan penolakan dokumen untuk EO..."></textarea>
        </div>

        <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
            <button type="button" id="btnApproveAction" class="btn btn-green"
                data-url="{{ route('admin.kyc.approve', $profile->id) }}" onclick="submitApprove(this)">
                ✓ Setujui (Approve Akun)
            </button>

            <button type="button" id="btnRejectToggle" class="btn btn-red" onclick="toggleRejectForm()">
                ✕ Tolak Pengajuan
            </button>

            <button type="button" id="btnSubmitReject" class="btn btn-red" style="display: none;"
                data-url="{{ route('admin.kyc.reject', $profile->id) }}" onclick="submitReject(this)">
                Konfirmasi Penolakan
            </button>
        </div>
    </div>
    @else
    <div class="card" style="display: flex; align-items: center; gap: 12px;">
        <span style="font-size: 14px; color: #64748B;">Status saat ini:</span>
        <span class="badge {{ $profile->verification_status }}">
            {{ strtoupper($profile->verification_status) }}
        </span>
    </div>
    @endif
</form>

<script>
    function setIndicator(cardId, status) {
        const card = document.getElementById(cardId);
        if (!card) return;
        if (status === 'valid') {
            card.style.borderLeft = '6px solid #16A34A';
            card.style.backgroundColor = '#F0FDF4';
        } else if (status === 'invalid') {
            card.style.borderLeft = '6px solid #DC2626';
            card.style.backgroundColor = '#FEF2F2';
        }
    }

    function toggleRejectForm() {
        const section = document.getElementById('rejectionSection');
        const submitBtn = document.getElementById('btnSubmitReject');
        const toggleBtn = document.getElementById('btnRejectToggle');
        if (section.style.display === 'none' || !section.style.display) {
            section.style.display = 'block';
            submitBtn.style.display = 'inline-flex';
            toggleBtn.textContent = 'Batal';
            document.getElementById('rejection_reason').focus();
        } else {
            section.style.display = 'none';
            submitBtn.style.display = 'none';
            toggleBtn.textContent = '✕ Tolak Pengajuan';
        }
    }

    function submitApprove(button) {
        const ktpValid = document.querySelector('input[name="ktp_check"]:checked');
        const npwpValid = document.querySelector('input[name="npwp_check"]:checked');

        if (!ktpValid || !npwpValid) {
            alert('Mohon periksa dan tandai status validasi untuk semua dokumen (KTP & NPWP) terlebih dahulu.');
            return;
        }

        if (ktpValid.value === 'invalid' || npwpValid.value === 'invalid') {
            alert('Tidak dapat menyetujui jika ada dokumen yang "Tidak Valid". Silakan gunakan opsi Tolak Pengajuan.');
            return;
        }

        const actionUrl = button.dataset.url;
        const form = document.getElementById('kycActionForm');
        form.action = actionUrl;
        form.method = 'POST';
        form.submit();
    }

    function submitReject(button) {
        const reason = document.getElementById('rejection_reason').value.trim();
        if (!reason) {
            alert('Alasan penolakan wajib diisi agar EO dapat memperbaikinya.');
            document.getElementById('rejection_reason').focus();
            return;
        }

        const actionUrl = button.dataset.url;
        const form = document.getElementById('kycActionForm');
        form.action = actionUrl;
        form.method = 'POST';
        form.submit();
    }
</script>
@endsection
