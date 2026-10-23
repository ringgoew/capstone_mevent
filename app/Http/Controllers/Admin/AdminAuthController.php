<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    public function login()
    {
        if (session('admin_login')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $emailAdmin = 'admin@event.test';
        $passwordAdmin = 'admin123';

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

        return back()
            ->withInput()
            ->withErrors([
                'email' => 'Email atau password salah.',
            ]);
    }

    public function logout()
    {
        session()->forget([
            'admin_login',
            'admin_name',
            'admin_email',
        ]);

        return redirect()
            ->route('home')
            ->with('success', 'Anda berhasil logout.');
    }
}
