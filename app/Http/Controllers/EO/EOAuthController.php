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
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        $normalizedEmail = strtolower(trim($validated['email']));

        // Cek apakah email sudah terdaftar di tabel users
        $existingUser = DB::table('users')
            ->whereRaw('LOWER(email) = ?', [$normalizedEmail])
            ->first();

        if ($existingUser) {
            // 1. Tolak jika email terdaftar sebagai role lain (admin / panitia / pembeli)
            if ($existingUser->role !== 'organizer') {
                return back()
                    ->withInput($request->except('password'))
                    ->withErrors([
                        'email' => 'Alamat email ini sudah terdaftar pada peran akun lain.',
                    ]);
            }

            // 2. Pastikan password yang dimasukkan cocok dengan akun yang sudah terdaftar
            if (!Hash::check($validated['password'], $existingUser->password)) {
                if ($existingUser->status === 'verified') {
                    return back()
                        ->withInput($request->except('password'))
                        ->withErrors([
                            'email' => 'Email ini sudah terdaftar dan terverifikasi. Silakan masuk melalui halaman Login EO.',
                        ]);
                }

                return back()
                    ->withInput($request->except('password'))
                    ->withErrors([
                        'password' => 'Email ini sudah terdaftar. Masukkan kata sandi yang sama seperti saat pendaftaran awal untuk melanjutkan pengisian berkas KYC.',
                    ]);
            }

            // 3. Password cocok — cek progres berkas KYC di tabel organizer_profiles
            $existingProfile = DB::table('organizer_profiles')
                ->where('user_id', $existingUser->id)
                ->first();

            // Jika akun sudah diverifikasi penuh oleh Admin
            if ($existingUser->status === 'verified' || ($existingProfile && $existingProfile->verification_status === 'verified')) {
                return redirect()->route('eo.login')
                    ->with('success', 'Akun EO Anda sudah terverifikasi. Silakan masuk ke Dashboard EO.');
            }

            // Jika berkas KYC sudah dikirim dan sedang ditinjau Admin
            if ($existingProfile && $existingProfile->verification_status === 'pending') {
                session([
                    'eo_user_id' => $existingUser->id,
                    'eo_nama'    => $existingUser->name,
                    'eo_email'   => $existingUser->email,
                ]);
                session()->forget(['eo_otp_hash', 'eo_otp_expires_at', 'eo_otp_verified']);

                return redirect()->route('eo.pending');
            }

            // Jika masih menunggu pengisian formulir KYC (belum ada di organizer_profiles) atau berstatus rejected:
            // Langsung arahkan ke formulir KYC tanpa mengulang registrasi & tanpa isi OTP lagi
            if (is_null($existingUser->email_verified_at)) {
                DB::table('users')
                    ->where('id', $existingUser->id)
                    ->update([
                        'email_verified_at' => now(),
                        'updated_at'        => now(),
                    ]);
            }

            session([
                'eo_user_id'      => $existingUser->id,
                'eo_nama'         => $existingUser->name,
                'eo_email'        => $existingUser->email,
                'eo_otp_verified' => true,
            ]);
            session()->forget(['eo_otp_hash', 'eo_otp_expires_at']);

            return redirect()->route('eo.kyc')
                ->with('success', 'Akun ditemukan! Silakan langsung lengkapi dokumen KYC Anda.');
        }

        // ─── Alur Pendaftaran EO Baru (Email Belum Pernah Terdaftar) ───────────
        $userId = DB::table('users')->insertGetId([
            'name'       => trim($validated['nama']),
            'email'      => $normalizedEmail,
            'password'   => Hash::make($validated['password']),
            'role'       => 'organizer',
            'status'     => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Buat OTP enam digit
        $otp = (string) random_int(100000, 999999);

        // Simpan hash OTP dan batas waktu 3 menit
        session([
            'eo_user_id'        => $userId,
            'eo_nama'           => trim($validated['nama']),
            'eo_email'          => $normalizedEmail,
            'eo_otp_hash'       => hash('sha256', $otp),
            'eo_otp_expires_at' => now()->addMinutes(3)->timestamp,
            'eo_otp_verified'   => false,
        ]);

        try {
            Mail::raw(
                "Halo {$validated['nama']},\n\n"
                    . "Kode OTP pendaftaran EO kamu adalah: {$otp}\n"
                    . "Kode ini berlaku selama 3 menit.\n\n"
                    . "Jangan bagikan kode ini kepada siapa pun.",
                function ($message) use ($normalizedEmail) {
                    $message->to($normalizedEmail)
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
        if (session('eo_login')) {
            return redirect()->route('eo.dashboard');
        }

        return view('eo.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $normalizedEmail = strtolower(trim($request->email));

        // Kunci session untuk menghitung kegagalan login spesifik email
        $attemptsKey = 'login_attempts_' . md5($normalizedEmail);
        $lockoutKey  = 'login_lockout_' . md5($normalizedEmail);

        // 1. Cek Apakah Akun Sedang Terblokir (Gagal >= 5 kali)
        if (session()->has($lockoutKey) && now()->timestamp < session($lockoutKey)) {
            $remainingSeconds = session($lockoutKey) - now()->timestamp;
            $remainingMinutes = ceil($remainingSeconds / 60);

            return back()
                ->withInput($request->only('email'))
                ->with('error', "Akun terblokir sementara karena gagal login 5 kali. Silakan coba lagi dalam {$remainingMinutes} menit.");
        }

        $user = DB::table('users')
            ->whereRaw('LOWER(email) = ?', [$normalizedEmail])
            ->where('role', 'organizer')
            ->first();

        // 2. Evaluasi Validitas Email & Password (Hanya proses gagal login jika email/password salah)
        if (!$user || !Hash::check($request->password, $user->password)) {
            $attempts = session($attemptsKey, 0) + 1;
            session([$attemptsKey => $attempts]);

            if ($attempts >= 5) {
                session([
                    $lockoutKey => now()->addMinutes(15)->timestamp,
                ]);
                session()->forget($attemptsKey);

                return back()
                    ->withInput($request->only('email'))
                    ->with('error', 'Anda telah gagal login 5 kali. Akun Anda diblokir sementara selama 15 menit.');
            }

            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Email atau password salah.');
        }

        // 3. Password Valid — Reset counter gagal login
        session()->forget([$attemptsKey, $lockoutKey]);

        // 4. Jika akun belum verified, arahkan sesuai tahapan KYC (form KYC atau halaman pending)
        if ($user->status !== 'verified') {
            $profile = DB::table('organizer_profiles')
                ->where('user_id', $user->id)
                ->first();

            session([
                'eo_user_id' => $user->id,
                'eo_nama'    => $user->name,
                'eo_email'   => $user->email,
            ]);

            if (!$profile || $profile->verification_status === 'rejected') {
                session(['eo_otp_verified' => true]);

                return redirect()->route('eo.kyc')
                    ->with('success', 'Silakan lengkapi berkas KYC Anda untuk melanjutkan proses verifikasi.');
            }

            return redirect()->route('eo.pending');
        }

        // 5. Akun sudah verified — Login penuh ke Dashboard EO
        $request->session()->regenerate();

        session([
            'eo_login'   => true,
            'eo_user_id' => $user->id,
            'eo_nama'    => $user->name,
            'eo_email'   => $user->email,
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
