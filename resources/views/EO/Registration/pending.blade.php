@extends('layouts.eo')
@section('title', 'Menunggu Verifikasi')
@section('content')

    <div class="container text-center">

        <div class="icon">
            ⏳
        </div>

        <h1>Pendaftaran Berhasil</h1>

        <div class="status">
            Pending Approval
        </div>

        <p>
            Berkas pendaftaran EO kamu sudah berhasil dikirim.
            Silakan menunggu proses verifikasi dari Admin.
        </p>

        <div class="info">
            Admin akan memeriksa data profil dan dokumen
            yang telah kamu kirim sebelum akun diaktifkan.
        </div>

        <a href="{{ route('home') }}" class="back">
            Kembali ke Home
        </a>

    </div>
@endsection