@extends('layouts.eo')
@section('title', 'Dashboard EO')
@section('content')

    <div class="container">

        <h1>Daftar sebagai EO</h1>

        <p class="subtitle">
            Daftarkan diri kamu sebagai Event Organizer.
        </p>

        @if ($errors->any())
            <div class="alert">
                <strong>Periksa kembali data pendaftaran:</strong>
                <ul style="margin: 8px 0 0 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="alert">{{ session('error') }}</div>
        @endif

        <form action="{{ route('eo.register.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="nama">Nama EO / Organisasi</label>
                <input
                    type="text"
                    id="nama"
                    name="nama"
                    value="{{ old('nama') }}"
                    placeholder="Masukkan nama EO"
                    maxlength="255"
                    required
                >
                @error('nama')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="penanggung_jawab">Nama Penanggung Jawab</label>
                <input
                    type="text"
                    id="penanggung_jawab"
                    name="penanggung_jawab"
                    value="{{ old('penanggung_jawab') }}"
                    placeholder="Masukkan nama penanggung jawab"
                    maxlength="255"
                    required
                >
                @error('penanggung_jawab')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Masukkan email"
                    maxlength="255"
                    required
                >
                @error('email')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="no_telp">No. Telepon</label>
                <input
                    type="tel"
                    id="no_telp"
                    name="no_telp"
                    value="{{ old('no_telp') }}"
                    placeholder="Masukkan nomor telepon"
                    maxlength="20"
                    required
                >
                @error('no_telp')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Minimal 8 karakter"
                    minlength="8"
                    required
                >
                @error('password')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    placeholder="Ulangi password"
                    minlength="8"
                    required
                >
            </div>

            <button type="submit">
                Daftar sebagai EO
            </button>
        </form>

        <a href="{{ route('home') }}" class="back">
            ← Kembali ke Home
        </a>

    </div>

    
@endsection
