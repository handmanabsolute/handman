<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password HandMan</title>
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
                            <h2 style="margin: 0 0 16px 0; font-size: 20px; font-weight: 700; color: #111827;">Atur Ulang Password</h2>
                            <p style="margin: 0 0 30px 0; font-size: 15px; line-height: 1.5; color: #4B5563;">
                                Anda menerima email ini karena kami menerima permintaan untuk mengatur ulang password akun HandMan Anda. Silakan klik tombol di bawah ini untuk melanjutkan:
                            </p>
                            
                            <div style="margin: 0 auto 30px auto; width: fit-content;">
                                <a href="{{ $resetLink }}" target="_blank" style="background-color: #3B28CC; color: #ffffff; text-decoration: none; padding: 14px 30px; border-radius: 9999px; font-size: 15px; font-weight: 600; display: inline-block; box-shadow: 0 4px 6px -1px rgba(59, 40, 204, 0.2), 0 2px 4px -1px rgba(59, 40, 204, 0.1);">Reset Password</a>
                            </div>
                            
                            <p style="margin: 0 0 10px 0; font-size: 13px; color: #EF4444; font-weight: 600;">
                                * Tautan reset password ini hanya berlaku selama 60 menit.
                            </p>
                            <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.5;">
                                Jika Anda tidak meminta pengaturan ulang password ini, Anda tidak perlu melakukan tindakan apa pun.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="background-color: #F9FAFB; padding: 20px; border-top: 1px solid #E5E7EB; color: #9CA3AF; font-size: 12px;">
                            <p style="margin: 0 0 5px 0;">Jika Anda mengalami kendala saat mengklik tombol "Reset Password", silakan salin dan tempel URL berikut ke browser Anda:</p>
                            <p style="margin: 0 0 15px 0; word-break: break-all; color: #3B28CC;">{{ $resetLink }}</p>
                            <p style="margin: 0;">&copy; 2026 HandMan. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
