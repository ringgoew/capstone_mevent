<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login EO — M!VENT</title>

    <!-- Google Fonts sesuai DESIGN.md -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lalezar&family=Montserrat:wght@500;600;700;800&family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        /* ==========================================================================
           M!VENT Auth System — Event Organizer Login (DESIGN.md Compliant)
           ========================================================================== */
        :root {
            --color-navy-dark: #2B293D;
            --color-navy-light: #383550;
            --color-navy-hover: #1C1B29;
            --color-bluish-grey: #2D2C3C;
            --color-yellow: #FFE047;
            --color-yellow-hover: #F5D328;
            --color-purple: #4539B4;
            --color-purple-soft: #EEECFB;

            --color-white: #FFFFFF;
            --color-bg-page: #F8F9FC;
            --color-surface-muted: #F1F3F6;
            --color-dark-grey: #5A5A5A;
            --color-muted-grey: #8C8C9E;
            --color-footer-muted: #A9A9B8;
            --color-border: #E4E4EC;
            --color-focus-blue: #4872C6;

            --font-logo: 'Lalezar', cursive;
            --font-heading: 'Montserrat', sans-serif;
            --font-body: 'Open Sans', sans-serif;

            --shadow-sm: 0 2px 10px rgba(43, 41, 61, 0.06);
            --shadow-md: 0 8px 24px rgba(43, 41, 61, 0.10);
            --transition: all 0.25s ease;
        }

        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            background-color: var(--color-navy-dark);
            color: var(--color-bluish-grey);
            font-family: var(--font-body);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        .auth-split-layout {
            display: flex;
            min-height: 100vh;
            width: 100%;
            background-color: var(--color-navy-dark);
        }

        /* ─── Panel Kiri: Brand & Visual Showcase ───────────────────────────── */
        .auth-brand-panel {
            width: 44%;
            background: linear-gradient(145deg, #2B293D 0%, #383550 58%, #4539B4 100%);
            color: var(--color-white);
            padding: 44px 68px 44px 52px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .auth-brand-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255, 224, 71, 0.15) 1px, transparent 1px);
            background-size: 26px 26px;
            pointer-events: none;
        }

        .brand-top,
        .brand-hero,
        .brand-bottom {
            position: relative;
            z-index: 2;
        }

        .logo-link {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: rgba(255, 224, 71, 0.15);
            border: 1.5px solid rgba(255, 224, 71, 0.4);
            color: var(--color-yellow);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transform: rotate(-8.15deg);
            transition: var(--transition);
        }

        .logo-link:hover .logo-icon {
            transform: rotate(0deg) scale(1.04);
            background-color: var(--color-yellow);
            color: var(--color-navy-dark);
        }

        .logo-text {
            font-family: var(--font-logo);
            font-size: 36px;
            line-height: 1;
            letter-spacing: 0.5px;
            color: var(--color-yellow);
        }

        .logo-text span {
            color: var(--color-white);
        }

        .brand-hero {
            margin: auto 0;
            padding: 32px 0;
        }

        .brand-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            border-radius: 50px;
            background-color: rgba(255, 224, 71, 0.14);
            border: 1px solid rgba(255, 224, 71, 0.35);
            font-family: var(--font-heading);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            color: var(--color-yellow);
            margin-bottom: 20px;
        }

        .brand-headline {
            font-family: var(--font-heading);
            font-size: 36px;
            font-weight: 800;
            line-height: 1.26;
            color: var(--color-white);
            letter-spacing: -0.4px;
            margin-bottom: 18px;
        }

        .brand-headline .highlight {
            color: var(--color-yellow);
        }

        .brand-subcopy {
            font-size: 15.5px;
            line-height: 1.7;
            color: #DCDCE8;
            max-width: 410px;
        }

        .brand-ticket-note {
            background: rgba(255, 255, 255, 0.08);
            border: 1px dashed rgba(255, 224, 71, 0.35);
            border-radius: 12px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 13px;
            color: var(--color-footer-muted);
        }

        .brand-ticket-note strong {
            color: var(--color-white);
            display: block;
            font-family: var(--font-heading);
            font-size: 13px;
            margin-bottom: 2px;
        }

        /* ─── Panel Kanan: Form Container Melengkung ────────────────────────── */
        .auth-form-panel {
            flex: 1;
            background-color: var(--color-white);
            border-top-left-radius: 38px;
            border-bottom-left-radius: 38px;
            margin-left: -28px;
            position: relative;
            z-index: 5;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 56px 32px;
            box-shadow: -12px 0 36px rgba(18, 17, 28, 0.22);
        }

        .btn-close-portal {
            position: absolute;
            top: 28px;
            right: 32px;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            border: 1.5px solid var(--color-border);
            background-color: var(--color-white);
            color: var(--color-dark-grey);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: var(--transition);
        }

        .btn-close-portal:hover {
            background-color: var(--color-navy-dark);
            border-color: var(--color-navy-dark);
            color: var(--color-yellow);
            transform: rotate(90deg);
        }

        .auth-form-box {
            width: 100%;
            max-width: 440px;
            animation: fadeInUp 0.45s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(14px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .portal-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 13px;
            border-radius: 50px;
            background-color: rgba(255, 224, 71, 0.28);
            color: var(--color-navy-dark);
            font-family: var(--font-heading);
            font-size: 11.5px;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .auth-title {
            font-family: var(--font-heading);
            font-size: 32px;
            font-weight: 800;
            color: var(--color-bluish-grey);
            line-height: 1.25;
            margin-bottom: 8px;
        }

        .auth-subtitle {
            font-size: 14.5px;
            color: var(--color-dark-grey);
            margin-bottom: 24px;
        }

        /* Quick Portal Switcher */
        .portal-switch-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-bottom: 22px;
        }

        .portal-switch-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            height: 44px;
            padding: 0 12px;
            border-radius: 10px;
            border: 1.5px solid var(--color-border);
            background-color: var(--color-white);
            color: var(--color-bluish-grey);
            font-family: var(--font-heading);
            font-size: 12.5px;
            font-weight: 700;
            text-decoration: none;
            transition: var(--transition);
        }

        .portal-switch-btn:hover {
            border-color: var(--color-navy-dark);
            background-color: var(--color-bg-page);
            transform: translateY(-1px);
        }

        .auth-divider {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 22px;
            color: var(--color-muted-grey);
            font-family: var(--font-heading);
            font-size: 11.5px;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }

        .auth-divider::before,
        .auth-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background-color: var(--color-border);
        }

        /* Alerts (DESIGN.md Standard) */
        .alert-box {
            padding: 13px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 13.5px;
            font-weight: 600;
            border: 1px solid transparent;
        }

        .alert-error {
            background-color: #FEE2E2;
            color: #991B1B;
            border-color: #FCA5A5;
        }

        .alert-success {
            background-color: #DCFCE7;
            color: #166534;
            border-color: #86EFAC;
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-family: var(--font-heading);
            font-size: 13px;
            font-weight: 700;
            color: var(--color-bluish-grey);
            margin-bottom: 7px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .figma-input {
            width: 100%;
            height: 52px;
            padding: 0 16px;
            border: 1.5px solid var(--color-border);
            border-radius: 10px;
            background-color: var(--color-white);
            font-family: var(--font-body);
            font-size: 14.5px;
            color: var(--color-bluish-grey);
            outline: none;
            transition: var(--transition);
        }

        .figma-input::placeholder {
            color: var(--color-muted-grey);
        }

        .figma-input:focus {
            border-color: var(--color-focus-blue);
            box-shadow: 0 0 0 3px rgba(72, 114, 198, 0.14);
        }

        .figma-input.has-toggle {
            padding-right: 48px;
        }

        .password-toggle-btn {
            position: absolute;
            right: 12px;
            width: 34px;
            height: 34px;
            border: none;
            background: transparent;
            color: var(--color-muted-grey);
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
        }

        .password-toggle-btn:hover {
            color: var(--color-navy-dark);
            background-color: var(--color-surface-muted);
        }

        .btn-submit-primary {
            width: 100%;
            height: 52px;
            margin-top: 8px;
            border: none;
            border-radius: 10px;
            background-color: var(--color-navy-dark);
            color: var(--color-yellow);
            font-family: var(--font-heading);
            font-size: 15px;
            font-weight: 800;
            letter-spacing: 0.2px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        .btn-submit-primary:hover {
            background-color: var(--color-navy-hover);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .auth-footer-link {
            margin-top: 22px;
            text-align: center;
            font-size: 14px;
            color: var(--color-dark-grey);
        }

        .auth-footer-link a {
            color: var(--color-purple);
            font-family: var(--font-heading);
            font-weight: 700;
            text-decoration: none;
            transition: var(--transition);
        }

        .auth-footer-link a:hover {
            color: var(--color-navy-dark);
            text-decoration: underline;
        }

        /* ─── Responsive Breakpoints ────────────────────────────────────────── */
        @media (max-width: 960px) {
            .auth-split-layout {
                flex-direction: column;
            }

            .auth-brand-panel {
                width: 100%;
                padding: 32px 24px 54px;
            }

            .brand-hero {
                padding: 24px 0 8px;
            }

            .brand-headline {
                font-size: 28px;
            }

            .brand-ticket-note {
                display: none;
            }

            .auth-form-panel {
                margin-left: 0;
                margin-top: -28px;
                border-top-left-radius: 28px;
                border-top-right-radius: 28px;
                border-bottom-left-radius: 0;
                padding: 40px 22px 48px;
            }

            .btn-close-portal {
                top: 20px;
                right: 20px;
                width: 38px;
                height: 38px;
            }
        }

        @media (max-width: 480px) {
            .portal-switch-grid {
                grid-template-columns: 1fr;
            }

            .auth-title {
                font-size: 26px;
            }
        }
    </style>
</head>

<body>

    <div class="auth-split-layout">
        <!-- Panel Kiri: Identitas M!VENT -->
        <aside class="auth-brand-panel">
            <div class="brand-top">
                <a href="{{ route('home') }}" class="logo-link">
                    <span class="logo-icon" aria-hidden="true">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z" />
                            <path d="M13 5v2" />
                            <path d="M13 17v2" />
                            <path d="M13 11v2" />
                        </svg>
                    </span>
                    <span class="logo-text">M<span>!</span>VENT</span>
                </a>
            </div>

            <div class="brand-hero">
                <span class="brand-eyebrow">Event Organizer Studio</span>
                <h2 class="brand-headline">
                    Wujudkan <span class="highlight">konser &amp; festival</span> impian. Masuk untuk kelola <span class="highlight">penjualan tiket!</span>
                </h2>
                <p class="brand-subcopy">
                    Atur kategori tiket, pantau kuota terjual secara real-time, dan kelola tim panitia lapangan Anda dalam satu dasbor terpadu.
                </p>
            </div>

            <div class="brand-bottom">
                <div class="brand-ticket-note">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#FFE047" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z" />
                        <path d="m9 12 2 2 4-4" />
                    </svg>
                    <div>
                        <strong>Mitra Penyelenggara Terverifikasi</strong>
                        Kelola publikasi event &amp; pencairan dana dengan transparan.
                    </div>
                </div>
            </div>
        </aside>

        <!-- Panel Kanan: Form Login -->
        <main class="auth-form-panel">
            <a href="{{ route('home') }}" class="btn-close-portal" title="Kembali ke Beranda" aria-label="Kembali ke Beranda">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </a>

            <div class="auth-form-box">
                <span class="portal-pill">Portal Event Organizer</span>
                <h1 class="auth-title">Login Penyelenggara</h1>
                <p class="auth-subtitle">Masuk untuk mengelola event, kategori tiket, dan tim panitia kamu.</p>

                <div class="auth-divider">Masuk dengan Akun EO</div>

                @if (session('error'))
                <div class="alert-box alert-error" role="alert">
                    {{ session('error') }}
                </div>
                @endif

                @if (session('success'))
                <div class="alert-box alert-success" role="alert">
                    {{ session('success') }}
                </div>
                @endif

                @if ($errors->any())
                <div class="alert-box alert-error" role="alert">
                    @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                    @endforeach
                </div>
                @endif

                <form action="{{ route('eo.login.authenticate') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="email" class="form-label">Alamat Email</label>
                        <div class="input-wrapper">
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="figma-input"
                                placeholder="nama@organizer.com"
                                value="{{ old('email') }}"
                                required
                                autofocus>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Kata Sandi</label>
                        <div class="input-wrapper">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="figma-input has-toggle"
                                placeholder="Masukkan kata sandi"
                                required>
                            <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('password', this)" aria-label="Tampilkan atau sembunyikan kata sandi">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit-primary">
                        <span>Masuk ke Dashboard EO</span>
                        <span aria-hidden="true">&rarr;</span>
                    </button>
                </form>

                <div class="auth-footer-link">
                    Belum memiliki akun EO?
                    <a href="{{ route('eo.register') }}">
                        Daftar sebagai EO
                    </a>
                </div>
            </div>
        </main>
    </div>

    <script>
        function togglePasswordVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            if (!input) return;
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            btn.style.color = isPassword ? '#4539B4' : '';
        }
    </script>
</body>

</html>