<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; 

use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    // Menampilkan halaman Login Admin
    public function login()
    {
        // Jika admin sudah login, langsung arahkan ke dashboard
        if (session('admin_login')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    // Proses autentikasi login admin
    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Data login admin dummy
        $emailAdmin = 'admin@event.test';
        $passwordAdmin = 'admin123';

        // Cek login
        if (
            $request->email === $emailAdmin &&
            $request->password === $passwordAdmin
        ) {
            session([
                'admin_login' => true,
                'admin_name' => 'Administrator',
                'admin_email' => $emailAdmin,
            ]);

            return redirect()
                ->route('admin.dashboard')
                ->with('success', 'Login berhasil. Selamat datang, Admin.');
        }

        // Jika login gagal, arahkan kembali ke halaman login dengan pesan error
        return back()
            ->withInput()
            ->withErrors([
                'email' => 'Email atau password salah.',
            ]);
    }

    // Logout admin
    public function logout()
    {
        session()->forget([
            'admin_login',
            'admin_name',
            'admin_email',
        ]);

        return redirect()
            ->route('admin.login')
            ->with('success', 'Anda berhasil logout.');
    }
}