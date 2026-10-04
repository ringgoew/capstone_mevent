<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    //menampilan halaman Login Admin
    public function login()
    {
        // Jika admin sudah login,
        // langsung arahkan ke dashboard
        if (session('admin_login')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    // proses autentikasi login admin
    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // data login admin dummy
        $emailAdmin = 'admin@event.test';
        $passwordAdmin = 'admin123';

        // cek login
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
                ->with(
                    'success',
                    'Login berhasil. Selamat datang, Admin.'
                );
        }

        // Jika login gagal, arahkan kembali ke halaman login dengan pesan error
        return back()
            ->withInput()
            ->withErrors([
                'email' => 'Email atau password salah.',
            ]);
    }


    // menampilkan halaman dashboard admin
    public function dashboard()
    {
        /*
        |--------------------------------------------------------------------------
        | CEK SESSION
        |--------------------------------------------------------------------------
        */

        if (!session('admin_login')) {
            return redirect()
                ->route('admin.login')
                ->withErrors([
                    'email' => 'Silakan login terlebih dahulu.',
                ]);
        }

        // data dummy untuk ditampilkan di dashboard
        $data = [
            'total_event' => 12,
            'event_pending' => 3,
            'total_eo' => 8,
            'total_tiket' => 245,
        ];


        return view(
            'admin.dashboard',
            compact('data')
        );
    }


    // logout admin
    public function logout()
    {
        session()->forget([
            'admin_login',
            'admin_name',
            'admin_email',
        ]);

        return redirect()
            ->route('admin.login')
            ->with(
                'success',
                'Anda berhasil logout.'
            );
    }
}
