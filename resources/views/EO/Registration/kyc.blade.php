@extends('layouts.eo_onboarding')
@section('title', 'Lengkapi Profil EO — M!VENT')
@section('content')

<div class="eoa-flow-shell">

    <!-- 4-STEP PROGRESS INDICATOR -->
    <div class="eoa-stepper-wrap" aria-label="Tahapan Pendaftaran Event Organizer">
        <div class="eoa-stepper">
            <div class="eoa-step is-completed">
                <div class="eoa-step-track">
                    <span class="eoa-step-line is-hidden"></span>
                    <span class="eoa-step-circle">✓</span>
                    <span class="eoa-step-line"></span>
                </div>
                <span class="eoa-step-label">Registrasi</span>
            </div>

            <div class="eoa-step is-completed">
                <div class="eoa-step-track">
                    <span class="eoa-step-line"></span>
                    <span class="eoa-step-circle">✓</span>
                    <span class="eoa-step-line"></span>
                </div>
                <span class="eoa-step-label">Verifikasi OTP</span>
            </div>

            <div class="eoa-step is-active">
                <div class="eoa-step-track">
                    <span class="eoa-step-line"></span>
                    <span class="eoa-step-circle">3</span>
                    <span class="eoa-step-line"></span>
                </div>
                <span class="eoa-step-label">Berkas KYC</span>
            </div>

            <div class="eoa-step">
                <div class="eoa-step-track">
                    <span class="eoa-step-line"></span>
                    <span class="eoa-step-circle">4</span>
                    <span class="eoa-step-line is-hidden"></span>
                </div>
                <span class="eoa-step-label">Verifikasi Admin</span>
            </div>
        </div>
    </div>

    <!-- KARTU FORMULIR KYC -->
    <div class="eoa-card eoa-card-lg">
        <div class="eoa-kyc-head">
            <div>
                <span class="eoa-step-eyebrow">TAHAP 3 DARI 4 • VERIFIKASI IDENTITAS</span>
                <h1>Lengkapi Profil EO</h1>
                <p class="eoa-subtitle">
                    Lengkapi data organisasi, dokumen identitas, dan informasi rekening untuk proses verifikasi akun Event Organizer kamu.
                </p>
            </div>
            <span class="eoa-req-badge">Wajib diisi (<span class="eoa-req">*</span>)</span>
        </div>

        @if (session('success'))
        <div class="eoa-alert eoa-alert-success" role="alert">{{ session('success') }}</div>
        @endif
        
        @if ($errors->any())
        <div class="eoa-alert eoa-alert-error" role="alert">
            <strong>Periksa kembali data berikut:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if (session('error'))
        <div class="eoa-alert eoa-alert-error" role="alert">{{ session('error') }}</div>
        @endif

        <form
            action="{{ route('eo.kyc.submit') }}"
            method="POST"
            enctype="multipart/form-data"
            class="eoa-form">
            @csrf

            <!-- BLOK 1: IDENTITAS ORGANISASI & KTP -->
            <div class="eoa-section-box">
                <div class="eoa-section-title">
                    <span class="eoa-section-num">1</span>
                    <div>
                        <h2>Identitas Organisasi &amp; Penanggung Jawab</h2>
                        <p>Data legalitas penyelenggara dan kartu identitas penanggung jawab event.</p>
                    </div>
                </div>

                <div class="eoa-form-group">
                    <label for="organization_name" class="eoa-label">Nama EO / Organisasi <span class="eoa-req">*</span></label>
                    <input
                        type="text"
                        id="organization_name"
                        name="organization_name"
                        class="eoa-input"
                        value="{{ old('organization_name', $profile->organization_name ?? '') }}"
                        placeholder="Masukkan nama EO / organisasi kamu"
                        maxlength="255"
                        required>
                </div>

                <div class="eoa-form-group">
                    <label for="ktp_number" class="eoa-label">Nomor Induk Kependudukan (NIK/KTP) <span class="eoa-req">*</span></label>
                    <input
                        type="text"
                        id="ktp_number"
                        name="ktp_number"
                        class="eoa-input"
                        value="{{ old('ktp_number', $profile->ktp_number ?? '') }}"
                        placeholder="16 digit nomor KTP"
                        maxlength="255"
                        required>
                </div>

                <div class="eoa-form-group">
                    <label for="ktp_image" class="eoa-label">Foto / Scan KTP Penanggung Jawab <span class="eoa-req">*</span></label>
                    <label for="ktp_image" class="eoa-file-dropzone" id="ktpDropzone">
                        <span class="eoa-file-btn">Pilih File KTP</span>
                        <span class="eoa-file-text" id="ktpFileName">Belum ada file dipilih</span>
                    </label>
                    <input
                        type="file"
                        id="ktp_image"
                        name="ktp_image"
                        class="eoa-file-hidden"
                        accept=".jpg,.jpeg,.png"
                        {{ empty($profile->ktp_image) ? 'required' : '' }}>
                    @if(isset($profile->ktp_image))
                    <p class="eoa-file-saved">✓ Dokumen KTP sebelumnya sudah tersimpan.</p>
                    @endif
                    <p class="eoa-field-hint">Format: JPG, JPEG, atau PNG. Maksimal 2 MB.</p>
                </div>
            </div>

            <!-- BLOK 2: DOKUMEN NPWP (OPSIONAL) -->
            <div class="eoa-section-box">
                <div class="eoa-section-title">
                    <span class="eoa-section-num">2</span>
                    <div>
                        <h2>Dokumen Pajak / NPWP <span class="eoa-opt-tag">Opsional</span></h2>
                        <p>Lampirkan NPWP badan usaha atau pribadi jika tersedia untuk kelengkapan administrasi.</p>
                    </div>
                </div>

                <div class="eoa-form-group">
                    <label for="npwp_number" class="eoa-label">Nomor NPWP (Opsional)</label>
                    <input
                        type="text"
                        id="npwp_number"
                        name="npwp_number"
                        class="eoa-input"
                        value="{{ old('npwp_number', $profile->npwp_number ?? '') }}"
                        placeholder="15 atau 16 digit nomor NPWP jika ada"
                        maxlength="255">
                </div>

                <div class="eoa-form-group">
                    <label for="npwp_image" class="eoa-label">Foto / Scan NPWP (Opsional)</label>
                    <label for="npwp_image" class="eoa-file-dropzone" id="npwpDropzone">
                        <span class="eoa-file-btn">Pilih File NPWP</span>
                        <span class="eoa-file-text" id="npwpFileName">Belum ada file dipilih</span>
                    </label>
                    <input
                        type="file"
                        id="npwp_image"
                        name="npwp_image"
                        class="eoa-file-hidden"
                        accept=".jpg,.jpeg,.png">
                    @if(isset($profile->npwp_image))
                    <p class="eoa-file-saved">✓ Dokumen NPWP sebelumnya sudah tersimpan.</p>
                    @endif
                    <p class="eoa-field-hint">Format: JPG, JPEG, atau PNG. Maksimal 2 MB.</p>
                </div>
            </div>

            <!-- BLOK 3: REKENING PENCAIRAN DANA -->
            <div class="eoa-section-box">
                <div class="eoa-section-title">
                    <span class="eoa-section-num">3</span>
                    <div>
                        <h2>Informasi Rekening Pencairan</h2>
                        <p>Rekening tujuan transfer pendapatan hasil penjualan tiket event Anda.</p>
                    </div>
                </div>

                <div class="eoa-info-banner">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                    <span>Pastikan nama pemilik rekening sesuai dengan identitas penanggung jawab atau badan usaha EO untuk menghindari kendala pencairan.</span>
                </div>

                <div class="eoa-grid-2">
                    <div class="eoa-form-group">
                        <label for="bank_name" class="eoa-label">Nama Bank Pencairan <span class="eoa-req">*</span></label>
                        <input
                            type="text"
                            id="bank_name"
                            name="bank_name"
                            class="eoa-input"
                            value="{{ old('bank_name', $profile->bank_name ?? '') }}"
                            placeholder="Contoh: BCA, Mandiri, BRI, BNI"
                            maxlength="255"
                            required>
                    </div>

                    <div class="eoa-form-group">
                        <label for="bank_account_number" class="eoa-label">Nomor Rekening Bank <span class="eoa-req">*</span></label>
                        <input
                            type="text"
                            id="bank_account_number"
                            name="bank_account_number"
                            class="eoa-input"
                            value="{{ old('bank_account_number', $profile->bank_account_number ?? '') }}"
                            placeholder="Masukkan nomor rekening yang aktif"
                            maxlength="255"
                            required>
                    </div>
                </div>

                <div class="eoa-form-group">
                    <label for="bank_account_holder" class="eoa-label">Nama Pemilik Rekening <span class="eoa-req">*</span></label>
                    <input
                        type="text"
                        id="bank_account_holder"
                        name="bank_account_holder"
                        class="eoa-input"
                        value="{{ old('bank_account_holder', $profile->bank_account_holder ?? '') }}"
                        placeholder="Harus sesuai dengan nama buku tabungan"
                        maxlength="255"
                        required>
                </div>
            </div>

            <div class="eoa-submit-bar">
                <p class="eoa-submit-note">Data Anda dienkripsi dan hanya digunakan untuk verifikasi mitra EO M!VENT.</p>
                <button type="submit" class="eoa-btn-navy eoa-btn-auto">
                    <span>Kirim Berkas Pendaftaran</span>
                    <span class="eoa-btn-arrow">→</span>
                </button>
            </div>
        </form>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function bindFileLabel(inputId, labelId, zoneId) {
            const input = document.getElementById(inputId);
            const label = document.getElementById(labelId);
            const zone = document.getElementById(zoneId);
            if (!input || !label) return;

            input.addEventListener('change', function() {
                if (input.files && input.files.length > 0) {
                    label.textContent = input.files[0].name;
                    if (zone) zone.classList.add('has-file');
                } else {
                    label.textContent = 'Belum ada file dipilih';
                    if (zone) zone.classList.remove('has-file');
                }
            });
        }

        bindFileLabel('ktp_image', 'ktpFileName', 'ktpDropzone');
        bindFileLabel('npwp_image', 'npwpFileName', 'npwpDropzone');
    });
</script>
@endsection