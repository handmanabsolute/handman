<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Verifikasi OTP HandMan</title>
</head>
<body style="margin: 0; padding: 0; background-color: #F3F4F6; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #F3F4F6; padding: 40px 0;">
        <tr>
            <td align="center">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 500px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);">
                    <tr>
                        <td align="center" style="background-color: #3B28CC; padding: 30px; color: #ffffff;">
                            <h1 style="margin: 0; font-size: 24px; font-weight: 700; letter-spacing: 0.5px;">HandMan</h1>
                            <p style="margin: 5px 0 0 0; font-size: 14px; opacity: 0.85;">Sistem Manajemen Tugas Kantor</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 40px 30px; text-align: center; color: #1F2937;">
                            <h2 style="margin: 0 0 16px 0; font-size: 20px; font-weight: 700; color: #111827;">Verifikasi Keamanan</h2>
                            <p style="margin: 0 0 30px 0; font-size: 15px; line-height: 1.5; color: #4B5563;">
                                Kami mendeteksi adanya percobaan masuk ke akun Anda. Gunakan kode OTP di bawah ini untuk menyelesaikan proses login:
                            </p>
                            
                            <div style="background-color: #F3F4F6; border-radius: 12px; padding: 20px; margin: 0 auto 30px auto; width: fit-content; border: 1px solid #E5E7EB;">
                                <span style="font-family: 'Courier New', Courier, monospace; font-size: 36px; font-weight: 800; color: #3B28CC; letter-spacing: 6px; display: inline-block;">{{ $otpCode }}</span>
                            </div>
                            
                            <p style="margin: 0 0 10px 0; font-size: 13px; color: #EF4444; font-weight: 600;">
                                * Kode OTP ini berlaku selama 10 menit.
                            </p>
                            <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.5;">
                                Jika Anda tidak melakukan login ini, silakan abaikan email ini atau segera hubungi Administrator.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="background-color: #F9FAFB; padding: 20px; border-top: 1px solid #E5E7EB; color: #9CA3AF; font-size: 12px;">
                            <p style="margin: 0 0 5px 0;">Email ini dikirimkan secara otomatis oleh sistem keamanan HandMan.</p>
                            <p style="margin: 0;">&copy; 2026 HandMan. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
