@extends('layouts.admin')
@section('title', 'Detail KYC EO')
@section('content')

    <a href="{{ route('admin.kyc.index') }}" class="btn btn-gray">
        &larr; Kembali ke Daftar KYC
    </a>

    <h1 style="margin-top:24px;">Pemeriksaan Dokumen EO</h1>

    <div class="card" id="card-ktp" style="transition: all 0.3s ease;">
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
            <strong>Tandai Status Dokumen:</strong><br>
            <label style="margin-right:15px; cursor:pointer;">
                <input type="radio" name="ktp_check" onchange="setIndicator('card-ktp', 'valid')"> Valid
            </label>
            <label style="cursor:pointer;">
                <input type="radio" name="ktp_check" onchange="setIndicator('card-ktp', 'invalid')"> Tidak Valid
            </label>
        </div>
        @endif
    </div>

    <div class="card" id="card-npwp" style="transition: all 0.3s ease;">
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

        @if ($profile->verification_status === 'pending' && $profile->npwp_image)
        <div class="validation-box" style="margin-top:15px; padding:10px; background:#f9f9f9; border-radius:6px;">
            <strong>Tandai Status Dokumen:</strong><br>
            <label style="margin-right:15px; cursor:pointer;">
                <input type="radio" name="npwp_check" onchange="setIndicator('card-npwp', 'valid')"> Valid
            </label>
            <label style="cursor:pointer;">
                <input type="radio" name="npwp_check" onchange="setIndicator('card-npwp', 'invalid')"> Tidak Valid
            </label>
        </div>
        @endif
    </div>

    <script>
        function setIndicator(cardId, status) {
            const card = document.getElementById(cardId);
            if (status === 'valid') {
                // Set warna hijau "Valid"
                card.style.borderLeft = '6px solid #28a745';
                card.style.backgroundColor = '#f4fdf6';
            } else if (status === 'invalid') {
                // Set warna merah "Invalid"
                card.style.borderLeft = '6px solid #dc3545';
                card.style.backgroundColor = '#fff5f5';
            }
        }
    </script>
@endsection