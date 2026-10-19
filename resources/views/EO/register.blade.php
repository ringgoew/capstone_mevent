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

            <button type="submit">
                Daftar sebagai EO
            </button>
        </form>

        <a href="{{ route('home') }}" class="back">
            ← Kembali ke Home
        </a>

    </div>

    
@endsection
