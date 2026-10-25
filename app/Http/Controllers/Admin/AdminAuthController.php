<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    // Menampilkan halaman login admin
    public function login()
    {
        if (session('admin_login')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    // Memproses login admin
    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Memeriksa email dan password admin
        $user = User::where('email', $request->email)
            ->where('role', 'admin')
            ->where('status', 'active')
            ->first();

        // Memeriksa akun dan password
        if (
            !$user ||
            !Hash::check($request->password, $user->password)
        ) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Email atau password salah.',
                ]);
        }

        // Membuat session login baru
        $request->session()->regenerate();

        session([
            'admin_login' => true,
            'admin_id' => $user->id,
            'admin_name' => $user->name,
            'admin_email' => $user->email,
        ]);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Login berhasil. Selamat datang, Admin.');
    }

    // Logout Admin
    public function logout(Request $request)
    {
        $request->session()->forget([
            'admin_login',
            'admin_id',
            'admin_name',
            'admin_email',
        ]);

        $request->session()->regenerateToken();

        return redirect()
            ->route('home')
            ->with('success', 'Anda berhasil logout.');
    }
}
