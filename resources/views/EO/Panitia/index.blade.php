@extends('layouts.eo')
@section('content')
    <div class="container">
        <h2>Manajemen Akun Panitia & Hak Akses</h2>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Form Add Panitia -->
        <div class="card mb-4">
            <div class="card-header">Tambah Panitia Baru</div>
            <div class="card-body">
                <form action="{{ route('panitia.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <!-- Menentukan Hak Akses-->
                    <div class="mb-3">
                        <label>Hak Akses (Role)</label>
                        <select name="role" class="form-control" required>
                            <option value="panitia_tiket">Panitia Tiket (Check-in)</option>
                            <option value="panitia_acara">Panitia Acara</option>
                            <option value="keamanan">Keamanan</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Akun</button>
                </form>
            </div>
        </div>

        <!-- Menampilkan Daftar Akun Panitia -->
        <div class="card">
            <div class="card-header" style="text-align: center;">Daftar Akun Panitia</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Hak Akses</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($panitia as $p)
                            <tr>
                                <td>{{ $p->name }}</td>
                                <td>{{ $p->email }}</td>
                                <td>{{ $p->role }}</td>
                                <td>
                                    <!-- Aksi Delete Panitia -->
                                    <form action="{{ route('panitia.destroy', $p->id) }}" method="POST"
                                        style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <!-- Meminta konfirmasi yakin ingin menghapus -->
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus akun ini?');">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
