@extends('admin.layout')

@section('title', 'Pemeriksaan KYC EO')

@section('content')

    <h1>Pemeriksaan Dokumen EO</h1>
    <p>Periksa profil dan dokumen yang dikirimkan oleh Event Organizer.</p>

    <div class="card">
        <form method="GET" action="{{ route('admin.kyc.index') }}">
            <div class="field">
                <label>Filter Status KYC</label>

                <select name="status">
                    <option value="">Semua Status</option>
                    <option value="pending" @selected($status === 'pending')>
                        Menunggu Verifikasi
                    </option>
                    <option value="verified" @selected($status === 'verified')>
                        Terverifikasi
                    </option>
                    <option value="rejected" @selected($status === 'rejected')>
                        Ditolak
                    </option>
                </select>
            </div>

            <button class="btn btn-dark" type="submit">Terapkan Filter</button>
            <a href="{{ route('admin.kyc.index') }}" class="btn btn-gray">Reset</a>
        </form>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nama Organisasi</th>
                        <th>Penanggung Jawab</th>
                        <th>Email</th>
                        <th>Status KYC</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($profiles as $profile)
                        <tr>
                            <td>{{ $profile->organization_name }}</td>
                            <td>{{ $profile->user_name }}</td>
                            <td>{{ $profile->user_email }}</td>
                            <td>
                                <span class="badge {{ $profile->verification_status }}">
                                    {{ strtoupper($profile->verification_status) }}
                                </span>
                            </td>
                            <td>
                                <a class="btn btn-dark" href="{{ route('admin.kyc.show', $profile->id) }}">
                                    Periksa Dokumen
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                Belum ada profil KYC yang sesuai dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
