<?php

namespace App\Http\Controllers\EO;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        // Buat akun EO
        $userId = DB::table('users')->insertGetId([
            'name' => $validated['nama'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'organizer',
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Buat OTP enam digit
        $otp = (string) random_int(100000, 999999);

        // Simpan hash OTP dan batas waktu 5 menit
        session([
            'eo_user_id' => $userId,
            'eo_nama' => $validated['nama'],
            'eo_email' => $validated['email'],
            'eo_otp_hash' => hash('sha256', $otp),
            'eo_otp_expires_at' => now()->addMinutes(3)->timestamp,
            'eo_otp_verified' => false,
        ]);

        try {
            Mail::raw(
                "Halo {$validated['nama']},\n\n"
                    . "Kode OTP pendaftaran EO kamu adalah: {$otp}\n"
                    . "Kode ini berlaku selama 3 menit.\n\n"
                    . "Jangan bagikan kode ini kepada siapa pun.",
                function ($message) use ($validated) {
                    $message->to($validated['email'])
                        ->subject('OTP Pendaftaran EO - M!VENT');
                }
            );
        } catch (\Throwable $e) {
            // Hapus akun jika email gagal dikirim
            DB::table('users')->where('id', $userId)->delete();

            session()->forget([
                'eo_user_id',
                'eo_nama',
                'eo_email',
                'eo_otp_hash',
                'eo_otp_expires_at',
                'eo_otp_verified',
            ]);

            report($e);

            return back()
                ->withInput($request->except('password'))
                ->with('error', 'Email OTP gagal dikirim. Periksa konfigurasi email lalu coba lagi.');
        }

        return redirect()->route('eo.otp')
            ->with('success', 'Kode OTP telah dikirim ke email kamu.');
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

        // Kunci session untuk menghitung kegagalan login spesifik email
        $attemptsKey = 'login_attempts_' . md5($request->email);
        $lockoutKey  = 'login_lockout_' . md5($request->email);

        // 1. Cek Apakah Akun Sedang Terblokir (Gagal >= 5 kali)
        if (session()->has($lockoutKey) && now()->timestamp < session($lockoutKey)) {
            $remainingSeconds = session($lockoutKey) - now()->timestamp;
            $remainingMinutes = ceil($remainingSeconds / 60);

            return back()
                ->withInput($request->only('email'))
                ->with('error', "Akun terblokir sementara karena gagal login 5 kali. Silakan coba lagi dalam {$remainingMinutes} menit.");
        }

        $user = DB::table('users')
            ->where('email', $request->email)
            ->where('role', 'organizer')
            ->first();

        // 2. Evaluasi Validitas Email & Password
        if (!$user || !Hash::check($request->password, $user->password)) {
            // Counter percobaan gagal +1
            $attempts = session($attemptsKey, 0) + 1;
            session([$attemptsKey => $attempts]);

            // Cek Apakah Gagal >= 5 Kali
            if ($attempts >= 5) {
                // Blokir akun sementara selama 15 menit
                session([
                    $lockoutKey => now()->addMinutes(15)->timestamp
                ]);
                // Reset counter setelah diblokir
                session()->forget($attemptsKey);

                return back()
                    ->withInput($request->only('email'))
                    ->with('error', 'Anda telah gagal login 5 kali. Akun Anda diblokir sementara selama 15 menit.');
            }

            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Email atau password salah.');
        }

        if ($user->status !== 'verified') {
            return back()->with('error', 'Akun EO tidak terverifikasi.');
        }

        // Reset counter gagal login jika berhasil masuk
        session()->forget([$attemptsKey, $lockoutKey]);

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
