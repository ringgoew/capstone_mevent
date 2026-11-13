@extends('layouts.eo')

@section('title', 'Manajemen Akun Panitia - M!VENT')

@section('content')
    <div class="panitia-wrapper">

        <!-- Topbar Header (Selaras dengan Dashboard) -->
        <div class="topbar">
            <div>
                <h1>Manajemen Akun Panitia</h1>
                <p class="subtitle">Tambah, pantau, dan kelola hak akses panitia untuk membantu event Anda.</p>
            </div>
            <div class="profile">
                {{ session('eo_nama', 'Event Organizer') }}
            </div>
        </div>

        <!-- Notifikasi -->
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <div class="panitia-layout-grid">

            <!-- BAGIAN KIRI: Form Tambah Panitia -->
            <div class="panitia-card">
                <h2 class="panitia-card-title">Tambah Panitia Baru</h2>

                <form action="{{ route('panitia.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="name" required placeholder="Contoh: Budiono Siregar">
                    </div>

                    <div class="form-group">
                        <label>Email Aktif</label>
                        <input type="email" name="email" required placeholder="panitia@gmail.com">
                    </div>

                    <div class="form-group">
                        <label>Password Sementara</label>
                        <input type="password" name="password" required minlength="6" placeholder="Minimal 6 Karakter">
                    </div>

                    <!-- Menggunakan tag button bawaan eo.css -->
                    <button type="submit">Simpan Akun Panitia</button>
                </form>
            </div>

            <!-- BAGIAN KANAN: Tabel Daftar Panitia -->
            <div class="panitia-card">
                <h2 class="panitia-card-title">Daftar Akun Panitia Aktif</h2>

                <div class="table-responsive">
                    <table class="panitia-table">
                        <thead>
                            <tr>
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th style="text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($panitia as $p)
                                <tr>
                                    <td style="font-weight: 600;">{{ $p->name }}</td>
                                    <td>{{ $p->email }}</td>
                                    <td style="text-align: center;">
                                        <form action="{{ route('panitia.destroy', $p->id) }}" method="POST"
                                            style="margin: 0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete"
                                                onclick="return confirm('Peringatan: Apakah Anda yakin ingin menghapus akun panitia {{ $p->name }}?');">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">
                                        <div class="empty-tickets" style="border: none; padding: 30px;">
                                            <p style="margin-bottom: 5px; font-weight: 600; color: #111;">Belum ada panitia
                                                yang terdaftar</p>
                                            <p style="font-size: 13px;">Gunakan form di sebelah kiri untuk menambahkan akun
                                                panitia.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
