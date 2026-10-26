<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun EO Disetujui</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; margin: 0; padding: 0; }
        .email-wrapper { width: 100%; background-color: #f4f7f6; padding: 40px 0; }
        .email-container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .email-header { background-color: #28a745; color: #ffffff; text-align: center; padding: 24px; }
        .email-header h1 { margin: 0; font-size: 24px; }
        .email-body { padding: 32px; color: #333333; line-height: 1.6; }
        .email-body h2 { color: #28a745; margin-top: 0; }
        .btn-action { display: inline-block; padding: 12px 24px; background-color: #28a745; color: #ffffff !important; text-decoration: none; border-radius: 6px; font-weight: bold; margin-top: 20px; }
        .email-footer { text-align: center; padding: 20px; font-size: 13px; color: #777777; background-color: #f9f9f9; border-top: 1px solid #eeeeee; }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <div class="email-header">
                <h1>M!VENT</h1>
            </div>
            <div class="email-body">
                <h2>Selamat, Akun Event Organizer Anda Telah Disetujui!</h2>
                <p>Halo, <strong>{{ $profile->user_name }}</strong>,</p>
                <p>Terima kasih telah mendaftar sebagai Event Organizer di platform kami. Kami telah melakukan pemeriksaan terhadap dokumen KYC (KTP, NPWP, dan Rekening Bank) untuk organisasi <strong>{{ $profile->organization_name }}</strong>.</p>
                <p>Dengan gembira kami sampaikan bahwa pengajuan verifikasi Anda <strong>TELAH DISETUJUI</strong>.</p>
                <p>Sekarang Anda sudah memiliki akses penuh ke Dashboard EO. Anda sudah bisa mulai membuat, mempublikasikan, dan menjual tiket untuk event-event Anda.</p>
                <div style="text-align: center;">
                    <a href="{{ route('eo.login') }}" class="btn-action">Masuk ke Dashboard EO</a>
                </div>
                <p style="margin-top: 30px;">Semoga sukses dengan event pertama Anda!<br>Tim M!VENT</p>
            </div>
            <div class="email-footer">
                &copy; {{ date('Y') }} M!VENT Event Management. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>