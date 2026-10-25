@extends('layouts.admin')
@section('title', 'Detail KYC EO')
@section('content')

<a href="{{ route('admin.kyc.index') }}" class="btn btn-gray">
    &larr; Kembali ke Daftar KYC
</a>

<h1 style="margin-top:24px;">Pemeriksaan Dokumen EO</h1>

{{-- Notifikasi Error / Success --}}
@if(session('error'))
<div style="background: #fff5f5; color: #dc3545; padding: 12px; border-radius: 6px; margin-bottom: 20px; border-left: 5px solid #dc3545;">
    {{ session('error') }}
</div>
@endif

{{-- Form Utama untuk Approve / Reject (Menyatukan validasi dokumen dan aksi) --}}
<form id="kycActionForm" method="POST" action="">
    @csrf

    {{-- CARD KTP --}}
    <div class="card" id="card-ktp" style="transition: all 0.3s ease; margin-bottom: 20px;">
        <h2>Dokumen KTP</h2>
        <div class="field">
            <label>Nomor KTP</label>
            <p>{{ $profile->ktp_number }}</p>
        </div>
        <div class="field">
            <label>Foto/Scan KTP</label>
            @if ($profile->ktp_image)
            <a href="{{ route('admin.kyc.document', ['id' => $profile->id, 'type' => 'ktp']) }}" target="_blank" rel="noopener" class="btn btn-dark">
                Buka Dokumen KTP
            </a>
            @else
            <p>Dokumen KTP belum tersedia.</p>
            @endif
        </div>

        @if ($profile->verification_status === 'pending')
        <div class="validation-box" style="margin-top:15px; padding:10px; background:#f9f9f9; border-radius:6px;">
            <strong>Tandai Status Dokumen <span style="color: red;">*</span>:</strong><br>
            <label style="margin-right:15px; cursor:pointer;">
                <input type="radio" name="ktp_check" value="valid" required onchange="setIndicator('card-ktp', 'valid')"> Valid
            </label>
            <label style="cursor:pointer;">
                <input type="radio" name="ktp_check" value="invalid" required onchange="setIndicator('card-ktp', 'invalid')"> Tidak Valid
            </label>
        </div>
        @endif
    </div>

    {{-- CARD NPWP --}}
    <div class="card" id="card-npwp" style="transition: all 0.3s ease; margin-bottom: 20px;">
        <h2>Dokumen NPWP</h2>
        <div class="field">
            <label>Nomor NPWP</label>
            <p>{{ $profile->npwp_number ?: 'Tidak diisi' }}</p>
        </div>
        <div class="field">
            <label>Foto/Scan NPWP</label>
            @if ($profile->npwp_image)
            <a href="{{ route('admin.kyc.document', ['id' => $profile->id, 'type' => 'npwp']) }}" target="_blank" rel="noopener" class="btn btn-dark">
                Buka Dokumen NPWP
            </a>
            @else
            <p>Dokumen NPWP tidak tersedia.</p>
            @endif
        </div>

        @if ($profile->verification_status === 'pending')
        <div class="validation-box" style="margin-top:15px; padding:10px; background:#f9f9f9; border-radius:6px;">
            <strong>Tandai Status Dokumen <span style="color: red;">*</span>:</strong><br>
            <label style="margin-right:15px; cursor:pointer;">
                <input type="radio" name="npwp_check" value="valid" required onchange="setIndicator('card-npwp', 'valid')"> Valid
            </label>
            <label style="cursor:pointer;">
                <input type="radio" name="npwp_check" value="invalid" required onchange="setIndicator('card-ktp', 'invalid')"> Tidak Valid
            </label>
        </div>
        @endif
    </div>

    {{-- CARD INFORMASI REKENING BANK --}}
    <div class="card" style="transition: all 0.3s ease; margin-bottom: 20px;">
        <h2>Informasi Rekening Bank</h2>
        <div class="field" style="margin-bottom: 10px;">
            <label style="font-weight: bold; color: #555;">Nama Bank</label>
            <p>{{ $profile->bank_name ?: 'Tidak diisi' }}</p>
        </div>
        <div class="field" style="margin-bottom: 10px;">
            <label style="font-weight: bold; color: #555;">Nomor Rekening</label>
            <p>{{ $profile->bank_account_number ?: 'Tidak diisi' }}</p>
        </div>
        <div class="field">
            <label style="font-weight: bold; color: #555;">Nama Pemilik Rekening</label>
            <p>{{ $profile->bank_account_holder ?: 'Tidak diisi' }}</p>
        </div>
    </div>

    {{-- TOMBOL AKSI & MODAL / FORM PENOLAKAN (Jika Status Pending) --}}
    @if ($profile->verification_status === 'pending')
    <div class="card" style="background: #fff; padding: 20px;">
        <h3>Keputusan Verifikasi KYC</h3>

        {{-- Input Alasan Penolakan (Hidden secara default, muncul jika klik Tolak) --}}
        <div id="rejectionSection" style="display: none; margin-bottom: 15px;">
            <label for="rejection_reason" style="font-weight: bold; color: #dc3545;">Alasan Penolakan <span style="color: red;">*</span>:</label>
            <textarea name="rejection_reason" id="rejection_reason" rows="4" class="form-control" style="width: 100%; margin-top: 5px; padding: 8px;" placeholder="Tuliskan alasan penolakan dokumen/KYC di sini..."></textarea>
        </div>

        <div style="display: flex; gap: 10px; margin-top: 15px;">
            <button type="button" id="btnApproveAction" class="btn" style="background: #28a745; color: white;"
                data-url="{{ route('admin.kyc.approve', $profile->id) }}" onclick="submitApprove(this)">
                Approve / Verifikasi Akun
            </button>

            <button type="button" id="btnRejectToggle" class="btn" style="background: #dc3545; color: white;" onclick="toggleRejectForm()">
                Tolak Pengajuan
            </button>

            <button type="submit" id="btnSubmitReject" class="btn" style="background: #dc3545; color: white; display: none;"
                data-url="{{ route('admin.kyc.reject', $profile->id) }}" onclick="submitReject(this)">
                Konfirmasi Penolakan
            </button>
        </div>
    </div>
    @endif
</form>

<script>
    function setIndicator(cardId, status) {
        const card = document.getElementById(cardId);
        if (status === 'valid') {
            card.style.borderLeft = '6px solid #28a745';
            card.style.backgroundColor = '#f4fdf6';
        } else if (status === 'invalid') {
            card.style.borderLeft = '6px solid #dc3545';
            card.style.backgroundColor = '#fff5f5';
        }
    }

    // Validasi sebelum Approve: Pastikan semua dokumen sudah diperiksa (dipilih radio button-nya) dan bernilai 'valid'
    // Fungsi Approve
    function submitApprove(button) {
        const ktpValid = document.querySelector('input[name="ktp_check"]:checked');
        const npwpValid = document.querySelector('input[name="npwp_check"]:checked');

        if (!ktpValid || !npwpValid) {
            alert('Mohon tandai status validasi untuk semua dokumen (KTP & NPWP) terlebih dahulu.');
            return;
        }

        if (ktpValid.value === 'invalid' || npwpValid.value === 'invalid') {
            alert('Tidak dapat melakukan Approve jika masih ada dokumen yang berstatus "Tidak Valid". Silakan gunakan tombol Tolak Pengajuan.');
            return;
        }

        const actionUrl = button.dataset.url;
        const form = document.getElementById('kycActionForm');
        form.action = actionUrl;
        form.method = 'POST';
        form.submit();
    }

    // Fungsi Reject
    function submitReject(button) {
        const reason = document.getElementById('rejection_reason').value.trim();
        if (!reason) {
            alert('Alasan penolakan wajib diisi.');
            document.getElementById('rejection_reason').focus();
            return;
        }

        const actionUrl = button.dataset.url;
        const form = document.getElementById('kycActionForm');
        form.action = actionUrl;
        form.method = 'POST';
        form.submit();
    }

    // Validasi sebelum Reject: Pastikan alasan penolakan terisi
    function submitReject(actionUrl) {
        const reason = document.getElementById('rejection_reason').value.trim();
        if (!reason) {
            alert('Alasan penolakan wajib diisi.');
            document.getElementById('rejection_reason').focus();
            return;
        }

        const form = document.getElementById('kycActionForm');
        form.action = actionUrl;
        form.method = 'POST';
        form.submit();
    }
</script>
@endsection