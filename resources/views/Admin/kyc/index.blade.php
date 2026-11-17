@extends('layouts.admin')
@section('title', 'Pemeriksaan KYC EO — M!VENT Admin')
@section('page-title', 'Pemeriksaan Dokumen KYC EO')
@section('page-subtitle', 'Verifikasi legalitas profil, identitas penanggung jawab, dan rekening Event Organizer.')

@section('content')

<!-- Ringkasan Status Verifikasi EO -->
<section class="stats" aria-label="Ringkasan Status KYC">
    <div class="stat-card stat-highlight">
        <div>
            <div class="stat-card-top">
                <span class="stat-title">Siap Direview (Pending)</span>
                <span class="stat-pill-tag">Prioritas</span>
            </div>
            <div class="stat-number warning">{{ $counts['pending'] ?? 0 }}</div>
            <div class="stat-caption">Berkas KYC sudah dikirim EO</div>
        </div>
    </div>

    <div class="stat-card">
        <div>
            <div class="stat-card-top">
                <span class="stat-title">EO Terverifikasi</span>
            </div>
            <div class="stat-number">{{ $counts['verified'] ?? 0 }}</div>
            <div class="stat-caption">Akun aktif &amp; dapat membuat event</div>
        </div>
    </div>

    <div class="stat-card">
        <div>
            <div class="stat-card-top">
                <span class="stat-title">Berkas Ditolak</span>
            </div>
            <div class="stat-number">{{ $counts['rejected'] ?? 0 }}</div>
            <div class="stat-caption">Menunggu perbaikan dokumen dari EO</div>
        </div>
    </div>

    <div class="stat-card">
        <div>
            <div class="stat-card-top">
                <span class="stat-title">Belum Unggah Berkas</span>
            </div>
            <div class="stat-number info">{{ count($incompleteOrganizers ?? []) }}</div>
            <div class="stat-caption">Terdaftar di akun, belum submit KYC</div>
        </div>
    </div>
</section>

<!-- Filter Status Verifikasi -->
<div class="card" style="padding: 20px 24px;">
    <form method="GET" action="{{ route('admin.kyc.index') }}" style="display: flex; gap: 14px; align-items: flex-end; flex-wrap: wrap;">
        <div class="field" style="margin-bottom: 0; min-width: 240px; flex: 1;">
            <label for="status">Filter Status Verifikasi</label>
            <select id="status" name="status">
                <option value="">Semua Status</option>
                <option value="pending" @selected(($status ?? '' )==='pending' )>Menunggu Verifikasi (Pending)</option>
                <option value="verified" @selected(($status ?? '' )==='verified' )>Terverifikasi (Verified)</option>
                <option value="rejected" @selected(($status ?? '' )==='rejected' )>Ditolak (Rejected)</option>
            </select>
        </div>

        <div style="display: flex; gap: 10px;">
            <button class="btn btn-yellow" type="submit">Terapkan Filter</button>
            <a href="{{ route('admin.kyc.index') }}" class="btn btn-gray">Reset</a>
        </div>
    </form>
</div>

<!-- Tabel 1: Berkas KYC yang Sudah Dikirim EO -->
<div class="card">
    <div class="card-header-flex">
        <div class="card-title-group">
            <h2>Daftar Pengajuan Berkas KYC</h2>
            <p>Menampilkan akun Event Organizer yang telah melengkapi formulir dan dokumen KYC.</p>
        </div>
        <span class="badge draft">{{ count($profiles) }} Pengajuan</span>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nama Organisasi</th>
                    <th>Penanggung Jawab</th>
                    <th>Email</th>
                    <th>Status KYC</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($profiles as $profile)
                <tr>
                    <td style="font-weight: 700;">{{ $profile->organization_name }}</td>
                    <td>{{ $profile->user_name }}</td>
                    <td>{{ $profile->user_email }}</td>
                    <td>
                        <span class="badge {{ $profile->verification_status }}">
                            {{ strtoupper($profile->verification_status) }}
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <a class="btn btn-navy btn-sm" href="{{ route('admin.kyc.show', $profile->id) }}">
                            Periksa Dokumen &rarr;
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 36px; color: var(--color-muted-grey);">
                        Belum ada pengajuan dokumen KYC yang sesuai dengan filter saat ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Tabel 2: Pendaftar Akun EO yang Belum Mengisi Berkas KYC -->
@if (isset($incompleteOrganizers) && count($incompleteOrganizers) > 0)
<div class="card">
    <div class="card-header-flex">
        <div class="card-title-group">
            <h2>Pendaftar EO Belum Melengkapi Berkas KYC</h2>
            <p>Akun berikut sudah terdaftar di sistem namun belum menyelesaikan tahap unggah KTP &amp; rekening.</p>
        </div>
        <span class="badge pending">{{ count($incompleteOrganizers) }} Akun</span>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nama Pendaftar</th>
                    <th>Email</th>
                    <th>Status Akun</th>
                    <th>Tahap Pendaftaran</th>
                </tr>
            </thead>
            <tbody>
                @foreach($incompleteOrganizers as $eoUser)
                <tr>
                    <td style="font-weight: 700;">{{ $eoUser->name }}</td>
                    <td>{{ $eoUser->email }}</td>
                    <td>
                        <span class="badge pending">{{ strtoupper($eoUser->status) }}</span>
                    </td>
                    <td style="color: var(--color-muted-grey); font-size: 13px;">
                        Menunggu pengisian formulir KYC oleh EO
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection