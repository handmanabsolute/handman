<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun HandMan Baru Anda</title>
</head>
<body style="margin: 0; padding: 0; background-color: #F3F4F6; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #F3F4F6; padding: 40px 0;">
        <tr>
            <td align="center">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 500px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);">
                    <!-- Header -->
                    <tr>
                        <td align="center" style="background-color: #3B28CC; padding: 35px 30px; color: #ffffff;">
                            <h1 style="margin: 0; font-size: 26px; font-weight: 700; letter-spacing: 0.5px;">HandMan</h1>
                            <p style="margin: 6px 0 0 0; font-size: 14px; opacity: 0.85; letter-spacing: 0.2px;">Sistem Manajemen Tugas Kantor</p>
                        </td>
                    </tr>
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px; text-align: left; color: #1F2937;">
                            <h2 style="margin: 0 0 16px 0; font-size: 20px; font-weight: 700; color: #111827; text-align: center;">Selamat Datang di HandMan!</h2>
                            <p style="margin: 0 0 20px 0; font-size: 15px; line-height: 1.6; color: #4B5563;">
                                Halo <strong>{{ $user->nama_lengkap }}</strong>,
                            </p>
                            <p style="margin: 0 0 24px 0; font-size: 15px; line-height: 1.6; color: #4B5563;">
                                Administrator telah membuatkan akun baru untuk Anda di platform HandMan. Berikut adalah detail kredensial masuk Anda:
                            </p>
                            
                            <!-- Credentials Box -->
                            <div style="background-color: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 12px; padding: 20px; margin-bottom: 28px;">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td width="30%" style="font-size: 14px; color: #6B7280; padding-bottom: 10px; font-weight: 500;">Email / Username:</td>
                                        <td style="font-size: 14px; color: #111827; padding-bottom: 10px; font-weight: 600;">{{ $user->email }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 14px; color: #6B7280; font-weight: 500;">Password:</td>
                                        <td style="font-size: 14px; color: #3B28CC; font-weight: 700; font-family: monospace; letter-spacing: 0.5px;">{{ $password }}</td>
                                    </tr>
                                </table>
                            </div>
                            
                            <p style="margin: 0 0 28px 0; font-size: 14px; line-height: 1.5; color: #EF4444; font-weight: 500; text-align: center;">
                                * Demi keamanan akun Anda, harap segera ubah password ini setelah Anda berhasil masuk ke dalam sistem.
                            </p>
                            
                            <!-- Action Button -->
                            <div style="text-align: center; margin-bottom: 16px;">
                                <a href="{{ route('login') }}" target="_blank" style="background-color: #3B28CC; color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 12px; font-size: 15px; font-weight: 600; display: inline-block; box-shadow: 0 4px 6px -1px rgba(59, 40, 204, 0.25), 0 2px 4px -1px rgba(59, 40, 204, 0.15); transition: background-color 0.2s;">Login ke HandMan</a>
                            </div>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td align="center" style="background-color: #F9FAFB; padding: 24px 30px; border-top: 1px solid #E5E7EB; color: #9CA3AF; font-size: 12px; line-height: 1.5;">
                            <p style="margin: 0 0 8px 0;">Jika tombol di atas tidak berfungsi, salin dan tempel link berikut ke browser Anda:</p>
                            <p style="margin: 0 0 16px 0; word-break: break-all; color: #3B28CC;"><a href="{{ route('login') }}" style="color: #3B28CC; text-decoration: none;">{{ route('login') }}</a></p>
                            <p style="margin: 0;">&copy; 2026 HandMan. Hak Cipta Dilindungi Undang-Undang.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
