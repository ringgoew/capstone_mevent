<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Akun EO Ditolak</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; margin: 0; padding: 0; }
        .email-wrapper { width: 100%; background-color: #f4f7f6; padding: 40px 0; }
        .email-container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .email-header { background-color: #dc3545; color: #ffffff; text-align: center; padding: 24px; }
        .email-header h1 { margin: 0; font-size: 24px; }
        .email-body { padding: 32px; color: #333333; line-height: 1.6; }
        .email-body h2 { color: #dc3545; margin-top: 0; }
        .reason-box { background-color: #fff5f5; border-left: 4px solid #dc3545; padding: 16px; margin: 20px 0; color: #a94442; border-radius: 0 4px 4px 0; }
        .btn-action { display: inline-block; padding: 12px 24px; background-color: #343a40; color: #ffffff !important; text-decoration: none; border-radius: 6px; font-weight: bold; margin-top: 20px; }
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
                <h2>Pemberitahuan Verifikasi Akun EO</h2>
                <p>Halo, <strong>{{ $profile->user_name }}</strong>,</p>
                <p>Terima kasih atas ketertarikan Anda untuk menjadi Event Organizer di platform kami bersama <strong>{{ $profile->organization_name }}</strong>.</p>
                <p>Setelah tim Admin kami melakukan pengecekan terhadap dokumen KYC yang Anda unggah, mohon maaf kami harus <strong>MENOLAK</strong> pengajuan Anda saat ini karena alasan berikut:</p>
                
                <!-- Kotak Alasan Penolakan -->
                <div class="reason-box">
                    <strong>Catatan dari Admin:</strong><br>
                    {{ $reason }}
                </div>
                
                <p>Jangan khawatir! Akun Anda belum dihapus. Anda dapat memperbaiki dan melengkapi dokumen atau informasi yang kurang tepat dengan mengunggah ulang data melalui Dashboard Anda.</p>
                
                <div style="text-align: center;">
                    <a href="{{ route('eo.login') }}" class="btn-action">Perbaiki Data Profil</a>
                </div>
                <p style="margin-top: 30px;">Jika ada pertanyaan lebih lanjut, silakan balas email ini.<br>Tim M!VENT</p>
            </div>
            <div class="email-footer">
                &copy; {{ date('Y') }} M!VENT Event Management. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>