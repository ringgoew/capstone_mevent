@extends('layouts.admin')
@section('title', 'Detail KYC EO')
@section('content')

    <a href="{{ route('admin.kyc.index') }}" class="btn btn-gray">
        &larr; Kembali ke Daftar KYC
    </a>

    <h1 style="margin-top:24px;">Pemeriksaan Dokumen EO</h1>

    <div class="card">
        <h2>Informasi Akun dan Organisasi</h2>

        <div class="field">
            <label>Nama Penanggung Jawab</label>
            <p>{{ $profile->user_name }}</p>
        </div>

        <div class="field">
            <label>Email Akun</label>
            <p>{{ $profile->user_email }}</p>
        </div>

        <div class="field">
            <label>Nama Organisasi</label>
            <p>{{ $profile->organization_name }}</p>
        </div>

        <div class="field">
            <label>Status KYC</label>
            <p>
                <span class="badge {{ $profile->verification_status }}">
                    {{ strtoupper($profile->verification_status) }}
                </span>
            </p>
        </div>

        @if ($profile->rejection_reason)
            <div class="field">
                <label>Alasan Penolakan Sebelumnya</label>
                <p>{{ $profile->rejection_reason }}</p>
            </div>
        @endif
    </div>

    <div class="card">
        <h2>Dokumen Identitas</h2>

        <div class="field">
            <label>Nomor KTP</label>
            <p>{{ $profile->ktp_number }}</p>
        </div>

        <div class="field">
            <label>Foto/Scan KTP</label>

            @if ($profile->ktp_image)
                <a href="{{ route('admin.kyc.document', [
                    'id' => $profile->id,
                    'type' => 'ktp',
                ]) }}"
                    target="_blank" rel="noopener" class="btn btn-dark">
                    Buka Dokumen KTP
                </a>
            @else
                <p>Dokumen KTP belum tersedia.</p>
            @endif
        </div>

        <hr style="border:0;border-top:1px solid #eee;margin:20px 0;">

        <div class="field">
            <label>Nomor NPWP</label>
            <p>{{ $profile->npwp_number ?: 'Tidak diisi' }}</p>
        </div>

        <div class="field">
            <label>Foto/Scan NPWP</label>

            @if ($profile->npwp_image)
                <a href="{{ route('admin.kyc.document', [
                    'id' => $profile->id,
                    'type' => 'npwp',
                ]) }}"
                    target="_blank" rel="noopener" class="btn btn-dark">
                    Buka Dokumen NPWP
                </a>
            @else
                <p>Dokumen NPWP tidak tersedia.</p>
            @endif
        </div>
    </div>

    <div class="card">
        <h2>Informasi Rekening Bank</h2>

        <div class="field">
            <label>Nama Bank</label>
            <p>{{ $profile->bank_name }}</p>
        </div>

        <div class="field">
            <label>Nomor Rekening</label>
            <p>{{ $profile->bank_account_number }}</p>
        </div>

        <div class="field">
            <label>Nama Pemilik Rekening</label>
            <p>{{ $profile->bank_account_holder }}</p>
        </div>
    </div>

    @if ($profile->verification_status === 'pending')
        <div class="card">
            <h2>Keputusan Verifikasi</h2>

            <p>
                Periksa kesesuaian nama, nomor identitas, dokumen pendukung,
                dan informasi rekening sebelum mengambil keputusan.
            </p>

            <form action="{{ route('admin.kyc.approve', $profile->id) }}" method="POST" style="margin-top:20px;"
                onsubmit="return confirm('Apakah dokumen EO ini sudah sesuai dan ingin diverifikasi?')">
                @csrf

                <button type="submit" class="btn btn-green">
                    Verifikasi KYC
                </button>
            </form>

            <hr style="border:0;border-top:1px solid #eee;margin:24px 0;">

            <h3>Tolak KYC</h3>

            <form action="{{ route('admin.kyc.reject', $profile->id) }}" method="POST" style="margin-top:15px;"
                onsubmit="return confirm('Yakin ingin menolak KYC EO ini?')">
                @csrf

                <div class="field">
                    <label>Alasan Penolakan</label>

                    <textarea name="rejection_reason" maxlength="2000"
                        placeholder="Contoh: Foto KTP tidak terbaca atau data rekening tidak sesuai." required>{{ old('rejection_reason') }}</textarea>
                </div>

                <button type="submit" class="btn btn-red">
                    Tolak KYC
                </button>
            </form>
        </div>
    @else
        <div class="card">
            <p>
                Profil ini sudah berstatus
                <strong>{{ strtoupper($profile->verification_status) }}</strong>.
                Keputusan baru tidak dapat dilakukan dari halaman ini.
            </p>
        </div>
    @endif

@endsection
