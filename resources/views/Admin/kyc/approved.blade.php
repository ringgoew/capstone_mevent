<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun EO Disetujui - M!VENT</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #F8FAFC; margin: 0; padding: 0; color: #0F172A; }
        .email-wrapper { width: 100%; background-color: #F8FAFC; padding: 40px 16px; }
        .email-container { max-width: 580px; margin: 0 auto; background-color: #FFFFFF; border-radius: 16px; overflow: hidden; border: 1px solid #E2E8F0; box-shadow: 0 4px 15px rgba(15, 23, 42, 0.05); }
        .email-header { background-color: #0F172A; color: #FFFFFF; text-align: center; padding: 28px 20px; }
        .email-header h1 { margin: 0; font-size: 26px; font-weight: 800; letter-spacing: -0.02em; }
        .email-header h1 span { color: #38BDF8; }
        .email-body { padding: 36px 32px; font-size: 15px; line-height: 1.6; color: #334155; }
        .badge-success { display: inline-block; padding: 6px 14px; background-color: #DCFCE7; color: #166534; font-size: 13px; font-weight: 700; border-radius: 9999px; margin-bottom: 16px; }
        .btn-action { display: inline-block; padding: 14px 28px; background-color: #38BDF8; color: #0C4A6E !important; text-decoration: none; border-radius: 10px; font-weight: 700; font-size: 15px; margin: 24px 0; }
        .email-footer { text-align: center; padding: 20px; font-size: 12px; color: #64748B; background-color: #F8FAFC; border-top: 1px solid #E2E8F0; }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <div class="email-header">
                <h1>M<span>!</span>VENT</h1>
            </div>
            <div class="email-body">
                <div>
                    <span class="badge-success">✓ Verifikasi KYC Berhasil</span>
                </div>
                <h2 style="margin: 0 0 16px 0; font-size: 22px; color: #0F172A;">Selamat, Pengajuan EO Anda Disetujui!</h2>
                <p>Halo <strong>{{ $profile->user_name }}</strong>,</p>
                <p>Terima kasih telah mendaftar sebagai Penyelenggara Event di platform M!VENT. Tim Administrator telah menyelesaikan validasi dokumen KYC (KTP, NPWP, dan Data Rekening) untuk organisasi <strong>{{ $profile->organization_name }}</strong>.</p>
                <p>Status akun Anda kini telah <strong>AKTIF PENUH</strong>. Anda dapat langsung masuk ke Dashboard EO untuk mulai merancang event, mengatur kuota tiket, dan mempublikasikan penjualan.</p>
                <div style="text-align: center;">
                    <a href="{{ route('eo.login') }}" class="btn-action">Masuk ke Dashboard EO →</a>
                </div>
                <p style="margin-top: 24px; font-size: 14px; color: #64748B;">Semoga event Anda berjalan lancar dan sukses!<br><strong>Tim Manajemen M!VENT</strong></p>
            </div>
            <div class="email-footer">
                &copy; {{ date('Y') }} M!VENT Official Platform. Seluruh hak cipta dilindungi undang-undang.
            </div>
        </div>
    </div>
</body>
</html>
