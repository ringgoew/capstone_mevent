@extends('layouts.eo_onboarding')
@section('title', 'Verifikasi Email - M!VENT')
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

            <div class="eoa-step is-active">
                <div class="eoa-step-track">
                    <span class="eoa-step-line"></span>
                    <span class="eoa-step-circle">2</span>
                    <span class="eoa-step-line"></span>
                </div>
                <span class="eoa-step-label">Verifikasi OTP</span>
            </div>

            <div class="eoa-step">
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

    <!-- KARTU VERIFIKASI OTP -->
    <div class="eoa-card eoa-card-sm">
        <div class="eoa-card-header">
            <div class="eoa-icon-badge eoa-badge-yellow">
                <svg width="26" height="26" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>

            <span class="eoa-step-eyebrow">TAHAP 2 DARI 4</span>
            <h1>Verifikasi Email Anda</h1>
            <p class="eoa-subtitle">
                Kami telah mengirimkan 6 digit kode OTP ke alamat email Anda. Masukkan kode tersebut untuk melanjutkan aktivasi akun EO.
            </p>
        </div>

        @if (session('error'))
        <div class="eoa-alert eoa-alert-error" role="alert">
            {{ session('error') }}
        </div>
        @endif

        @if (session('success'))
        <div class="eoa-alert eoa-alert-success" role="alert">
            {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('eo.otp.verify') }}" method="POST" class="eoa-form">
            @csrf

            <div class="eoa-form-group">
                <label for="otp" class="eoa-label eoa-label-center">Kode OTP (6 Digit) <span class="eoa-req">*</span></label>
                <input
                    type="text"
                    id="otp"
                    name="otp"
                    class="otp-input eoa-otp-input"
                    placeholder="••••••"
                    inputmode="numeric"
                    pattern="[0-9]{6}"
                    maxlength="6"
                    autocomplete="one-time-code"
                    required
                    autofocus>
                <p class="eoa-field-hint eoa-text-center">Periksa folder Kotak Masuk atau Spam pada email yang Anda daftarkan.</p>
            </div>

            <button type="submit" class="eoa-btn-navy">
                <span>Verifikasi &amp; Lanjutkan</span>
                <span class="eoa-btn-arrow">→</span>
            </button>
        </form>
    </div>

</div>
@endsection