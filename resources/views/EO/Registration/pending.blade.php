@extends('layouts.eo_onboarding')
@section('title', 'Status Verifikasi EO — M!VENT')
@section('content')

@php
$verificationStatus = $profile->verification_status ?? 'pending';
@endphp

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

            <div class="eoa-step is-completed">
                <div class="eoa-step-track">
                    <span class="eoa-step-line"></span>
                    <span class="eoa-step-circle">✓</span>
                    <span class="eoa-step-line"></span>
                </div>
                <span class="eoa-step-label">Berkas KYC</span>
            </div>

            <div class="eoa-step {{ $verificationStatus === 'verified' ? 'is-completed' : 'is-active' }}">
                <div class="eoa-step-track">
                    <span class="eoa-step-line"></span>
                    <span class="eoa-step-circle">{{ $verificationStatus === 'verified' ? '✓' : '4' }}</span>
                    <span class="eoa-step-line is-hidden"></span>
                </div>
                <span class="eoa-step-label">Verifikasi Admin</span>
            </div>
        </div>
    </div>

    <!-- KARTU STATUS VERIFIKASI -->
    <div class="eoa-card eoa-card-md eoa-pending-card">
        <div class="eoa-pending-top-bar"></div>

        @if ($verificationStatus === 'rejected')
        <div class="eoa-card-header">
            <div class="eoa-icon-badge" style="background: #FEE2E2; color: #991B1B;">
                <svg width="30" height="30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86l-8.48 14.7A1 1 0 002.68 20h18.64a1 1 0 00.87-1.44l-8.48-14.7a1 1 0 00-1.74 0z" />
                </svg>
            </div>

            <h1>Pengajuan Perlu Diperbaiki</h1>

            <div class="eoa-pending-pill" style="background: #FEE2E2; color: #991B1B; border-color: #FCA5A5;">
                <span>Status: Ditolak (Rejected)</span>
            </div>

            <p class="eoa-subtitle eoa-pending-desc">
                Mohon maaf, berkas KYC Anda belum memenuhi kriteria verifikasi. Silakan perbaiki dokumen sesuai catatan berikut dan kirim ulang.
            </p>
        </div>

        @if (!empty($profile->rejection_reason))
        <div class="eoa-alert eoa-alert-error" role="alert" style="margin-bottom: 20px;">
            <strong>Alasan Penolakan Admin:</strong>
            <p style="margin-top: 4px;">{{ $profile->rejection_reason }}</p>
        </div>
        @endif

        <a href="{{ route('eo.kyc') }}" class="eoa-btn-navy" style="margin-bottom: 12px;">
            <span>Perbaiki &amp; Kirim Ulang Berkas KYC</span>
            <span class="eoa-btn-arrow">→</span>
        </a>

        <a href="{{ route('home') }}" class="eoa-btn-yellow">
            <span>Kembali ke Halaman Utama</span>
        </a>

        @elseif ($verificationStatus === 'verified')
        <div class="eoa-card-header">
            <div class="eoa-icon-badge" style="background: #DCFCE7; color: #166534;">
                <svg width="30" height="30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <h1>Akun EO Terverifikasi!</h1>

            <div class="eoa-pending-pill" style="background: #DCFCE7; color: #166534; border-color: #86EFAC;">
                <span>Status: Verified</span>
            </div>

            <p class="eoa-subtitle eoa-pending-desc">
                Selamat! Akun Event Organizer Anda telah disetujui. Sekarang Anda dapat mengakses Dashboard EO untuk membuat event dan menjual tiket.
            </p>
        </div>

        <a href="{{ route('eo.dashboard') }}" class="eoa-btn-navy">
            <span>Masuk ke Dashboard EO</span>
            <span class="eoa-btn-arrow">→</span>
        </a>

        @else
        <div class="eoa-card-header">
            <div class="eoa-icon-badge eoa-badge-yellow">
                <svg width="30" height="30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            <h1>Pengajuan Terkirim!</h1>

            <div class="eoa-pending-pill">
                <span class="eoa-pulse-dot"></span>
                <span>Menunggu Verifikasi Admin (Pending)</span>
            </div>

            <p class="eoa-subtitle eoa-pending-desc">
                Berkas pendaftaran KYC Event Organizer Anda telah kami terima. Tim Administrator M!VENT sedang memeriksa kelayakan dokumen identitas (KTP &amp; NPWP) Anda.
            </p>
        </div>

        <!-- Ringkasan Status 3 Langkah -->
        <div class="eoa-status-summary-grid">
            <div class="eoa-summary-cell">
                <span class="eoa-summary-label">Akun &amp; Email</span>
                <strong class="eoa-summary-ok">✓ Terverifikasi</strong>
            </div>
            <div class="eoa-summary-cell">
                <span class="eoa-summary-label">Berkas KYC</span>
                <strong class="eoa-summary-ok">✓ Diterima</strong>
            </div>
            <div class="eoa-summary-cell">
                <span class="eoa-summary-label">Review Admin</span>
                <strong class="eoa-summary-wait">⏳ Dalam Proses</strong>
            </div>
        </div>

        <div class="eoa-perforation" aria-hidden="true"></div>

        <div class="eoa-callout-box">
            <span class="eoa-callout-icon" aria-hidden="true">💡</span>
            <div>
                <strong>Catatan Estimasi Waktu:</strong> Hasil verifikasi akan kami kirimkan ke email Anda dalam waktu <strong>1x24 jam kerja</strong>. Setelah disetujui, akun Anda dapat langsung membuat dan menjual tiket event.
            </div>
        </div>

        <a href="{{ route('home') }}" class="eoa-btn-yellow">
            <span>Kembali ke Halaman Utama</span>
        </a>
        @endif
    </div>

    <!-- KARTU BANTUAN RINGKAS -->
    <div class="eoa-help-strip">
        <p>Pastikan alamat email yang didaftarkan aktif untuk menerima pemberitahuan hasil verifikasi dari tim <strong>M!VENT</strong>.</p>
    </div>

</div>
@endsection