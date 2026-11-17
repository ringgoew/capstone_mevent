<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar sebagai EO — M!VENT</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/eo.css'])
    @else
    <link rel="stylesheet" href="{{ asset('css/eo.css') }}">
    @endif
</head>

<body class="eoa-body">

    <!-- NAVBAR DEEP NAVY M!VENT -->
    <nav class="eoa-navbar">
        <div class="eoa-navbar-inner">
            <a href="{{ route('home') }}" class="eoa-logo-link">
                <span class="eoa-logo-icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 28" fill="none">
                        <path d="M4 0H36C38.2091 0 40 1.79086 40 4V9.5C37.5147 9.5 35.5 11.5147 35.5 14C35.5 16.4853 37.5147 18.5 40 18.5V24C40 26.2091 38.2091 28 36 28H4C1.79086 28 0 26.2091 0 24V18.5C2.48528 18.5 4.5 16.4853 4.5 14C4.5 11.5147 2.48528 9.5 0 9.5V4C0 1.79086 1.79086 0 4 0Z" fill="#FFE047" />
                        <line x1="12.5" y1="3" x2="12.5" y2="25" stroke="#2B293D" stroke-width="2" stroke-dasharray="2.5 2.5" stroke-linecap="round" />
                        <path d="M25.5 8.2L27.15 11.55L30.85 12.09L28.17 14.7L28.8 18.38L25.5 16.64L22.2 18.38L22.83 14.7L20.15 12.09L23.85 11.55L25.5 8.2Z" fill="#2B293D" />
                    </svg>
                </span>
                <span class="eoa-logo-text">M<span class="eoa-logo-bang">!</span>VENT</span>
            </a>

            <a href="{{ route('home') }}" class="eoa-nav-back">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                <span>Kembali ke Beranda</span>
            </a>
        </div>
    </nav>

    <main class="eoa-main">
        <!-- 4-STEP PROGRESS INDICATOR -->
        <div class="eoa-stepper-wrap" aria-label="Tahapan Pendaftaran Event Organizer">
            <div class="eoa-stepper">
                <div class="eoa-step is-active">
                    <div class="eoa-step-track">
                        <span class="eoa-step-line is-hidden"></span>
                        <span class="eoa-step-circle">1</span>
                        <span class="eoa-step-line"></span>
                    </div>
                    <span class="eoa-step-label">Registrasi</span>
                </div>

                <div class="eoa-step">
                    <div class="eoa-step-track">
                        <span class="eoa-step-line"></span>
                        <span class="eoa-step-circle">2</span>
                        <span class="eoa-step-line"></span>
                    </div>
                    <span class="eoa-step-label">Verifikasi OTP</span>
                </div>

                <div class="eoa-step">
                    <div class="eoa-step-track">
                        <span class="eoa-step-line"></span>
                        <span class="eoa-step-circle">3</span>
                        <span class="eoa-step-line"></span>
                    </div>
                    <span class="eoa-step-label">Berkas KYC</span>
                </div>

                <div class="eoa-step">
                    <div class="eoa-step-track">
                        <span class="eoa-step-line"></span>
                        <span class="eoa-step-circle">4</span>
                        <span class="eoa-step-line is-hidden"></span>
                    </div>
                    <span class="eoa-step-label">Verifikasi Admin</span>
                </div>
            </div>
        </div>

        <!-- KARTU REGISTRASI -->
        <div class="eoa-card eoa-card-sm">
            <div class="eoa-card-header">
                <div class="eoa-icon-badge eoa-badge-purple">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8.5" cy="7" r="4"></circle>
                        <line x1="20" y1="8" x2="20" y2="14"></line>
                        <line x1="23" y1="11" x2="17" y2="11"></line>
                    </svg>
                </div>
                <h1>Daftar sebagai EO</h1>
                <p class="eoa-subtitle">
                    Bergabunglah sebagai Event Organizer dan mulai kelola event serta penjualan tiket kamu di M!VENT.
                </p>
            </div>

            @if ($errors->any())
            <div class="eoa-alert eoa-alert-error" role="alert">
                <strong>Periksa kembali data pendaftaran:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if (session('error'))
            <div class="eoa-alert eoa-alert-error" role="alert">{{ session('error') }}</div>
            @endif

            <form action="{{ route('eo.register.store') }}" method="POST" class="eoa-form">
                @csrf

                <div class="eoa-form-group">
                    <label for="nama" class="eoa-label">Nama Lengkap / Nama EO <span class="eoa-req">*</span></label>
                    <input
                        type="text"
                        id="nama"
                        name="nama"
                        class="eoa-input"
                        value="{{ old('nama') }}"
                        placeholder="Contoh: Budi Organizer"
                        maxlength="255"
                        required
                        autofocus>
                    @error('nama')
                    <p class="eoa-field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="eoa-form-group">
                    <label for="email" class="eoa-label">Alamat Email <span class="eoa-req">*</span></label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="eoa-input"
                        value="{{ old('email') }}"
                        placeholder="nama@organizer.com"
                        maxlength="255"
                        required>
                    @error('email')
                    <p class="eoa-field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="eoa-form-group">
                    <label for="password" class="eoa-label">Kata Sandi <span class="eoa-req">*</span></label>
                    <div class="eoa-password-wrap">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="eoa-input eoa-input-pw"
                            placeholder="Minimal 8 karakter"
                            minlength="8"
                            required>
                        <button type="button" class="eoa-pw-toggle" id="eoaTogglePw" aria-label="Tampilkan atau sembunyikan kata sandi">
                            <svg id="eoaEyeIcon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                    <p class="eoa-field-error">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="eoa-btn-navy">
                    <span>Daftar Sekarang</span>
                    <span class="eoa-btn-arrow">→</span>
                </button>
            </form>

            <div class="eoa-perforation" aria-hidden="true"></div>

            <div class="eoa-card-footer-link">
                <span>Sudah memiliki akun EO?</span>
                <a href="{{ route('eo.login') }}">Masuk di sini</a>
            </div>
        </div>
    </main>

    <footer class="eoa-footer">
        <p>&copy; {{ date('Y') }} <strong>M!VENT</strong> — Platform Manajemen Event &amp; Tiket Konser.</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('eoaTogglePw');
            const pwInput = document.getElementById('password');
            if (toggleBtn && pwInput) {
                toggleBtn.addEventListener('click', function() {
                    const isHidden = pwInput.type === 'password';
                    pwInput.type = isHidden ? 'text' : 'password';
                });
            }
        });
    </script>
</body>

</html>