<?php

namespace App\Http\Controllers\EO;

use App\Http\Controllers\Controller; 

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EOAuthController extends Controller
{
    // Halaman registrasi EO
    public function register()
    {
        if (session('eo_login')) {
            return redirect()->route('eo.dashboard');
        }

        session()->forget(['eo_user_id', 'eo_otp_verified']);

        return view('eo.registration.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $userId = DB::table('users')->insertGetId([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'organizer',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        session()->forget('eo_login');

        session([
            'eo_user_id' => $userId,
            'eo_nama' => $request->nama,
            'eo_email' => $request->email,
            'eo_otp_verified' => false,
        ]);

        return redirect()->route('eo.otp');
    }

    public function login()
    {
        return view('eo.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = DB::table('users')
            ->where('email', $request->email)
            ->where('role', 'organizer')
            ->first();

        if (
            !$user ||
            !Hash::check($request->password, $user->password)
        ) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Email atau password salah.');
        }

        if ($user->status !== 'active') {
            return back()->with('error', 'Akun EO tidak aktif.');
        }

        $request->session()->regenerate();

        session([
            'eo_login' => true,
            'eo_user_id' => $user->id,
            'eo_nama' => $user->name,
            'eo_email' => $user->email,
        ]);

        return redirect()->route('eo.dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}