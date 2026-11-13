<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PanitiaAuthController extends Controller
{
    // Menampilkan halaman form login
    public function login()
    {
        return view('eo.panitia.login');
    }

    // Memproses data login
    public function authenticate(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'name' => 'required',
            'password' => 'required'
        ]);

        // Cek kecocokan email dan password di database
        if (Auth::attempt($credentials)) {

            // Jika cocok, cek apakah role-nya benar-benar 'panitia'
            if (Auth::user()->role === 'panitia') {
                $request->session()->regenerate();

                // Arahkan ke halaman khusus panitia (sementara arahkan ke URL /panitia/scan atau sejenisnya)
                return redirect()->intended('/panitia/dashboard');
            } else {
                // Jika bukan panitia (misal EO/Admin mencoba login di sini), tolak!
                Auth::logout();
                return back()->with('error', 'Akses ditolak! Akun Anda bukan terdaftar sebagai panitia.');
            }
        }

        // Jika email/password salah
        return back()->with('error', 'Email atau password yang Anda masukkan salah.');
    }

    // Memproses proses logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('panitia.login');
    }
}
