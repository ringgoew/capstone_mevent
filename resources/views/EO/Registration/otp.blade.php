@extends('layouts.eo')
@section('title', 'Verifikasi Email')
@section('content')

<div class="container text-center" style="max-width: 450px;">

        <h1>Verifikasi Email</h1>

        <p class="subtitle">
            Masukkan kode OTP yang dikirim ke email kamu
            untuk melanjutkan pendaftaran sebagai EO.
        </p>

        @if (session('error'))
            <div class="alert">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('eo.otp.verify') }}" method="POST">
            @csrf

            <div class="form-group text-left">
                <label for="otp">Kode OTP</label>
                <input
                    type="text"
                    id="otp"
                    name="otp"
                    class="otp-input"
                    placeholder="Masukkan 6 digit OTP"
                    maxlength="6"
                    required
                >
            </div>

            <button type="submit">
                Verifikasi OTP
            </button>
        </form>

        <div class="info">
            <strong>Demo:</strong> gunakan kode OTP <strong>123456</strong>
        </div>

    </div>
@endsection