@extends('layouts.eo')
@section('title', 'Dashboard EO')
@section('content')


    <nav>
        <div class="logo">
            Event POOKIEMAX
        </div>
    </nav>

    <div class="container">

        <h1>Verifikasi Email</h1>

        <p class="subtitle">
            Masukkan kode OTP yang dikirim ke email kamu
            untuk melanjutkan pendaftaran sebagai EO.
        </p>

        @if (session('error'))
            <div class="error">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('eo.otp.verify') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Kode OTP</label>

                <input
                    type="text"
                    name="otp"
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
            <strong>Demo:</strong> gunakan kode OTP
            <strong>123456</strong>
        </div>

    </div>

    
@endsection