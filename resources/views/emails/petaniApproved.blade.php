<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Disetujui</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f3f4f6;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f3f4f6; padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">

                    {{-- Header --}}
                    <tr>
                        <td style="background: linear-gradient(135deg, #4a7c2c 0%, #2d5016 100%); padding: 40px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 28px;">SALAKITA</h1>
                        </td>
                    </tr>

                    {{-- Content --}}
                    <tr>
                        <td style="padding: 40px;">
                            <div style="text-align: center; margin-bottom: 30px;">
                                <div style="display: inline-block; width: 80px; height: 80px; background-color: #dcfce7; border-radius: 50%; line-height: 80px;">
                                    <span style="color: #16a34a; font-size: 40px;">✓</span>
                                </div>
                            </div>

                            <h2 style="color: #2d5016; text-align: center; margin-bottom: 20px;">Selamat! Pendaftaran Anda Disetujui</h2>

                            <p style="color: #4b5563; line-height: 1.6; margin-bottom: 20px;">
                                Halo <strong>{{ $petani->name }}</strong>,
                            </p>

                            <p style="color: #4b5563; line-height: 1.6; margin-bottom: 20px;">
                                Pendaftaran Anda sebagai petani di platform SALAKITA telah <strong style="color: #16a34a;">disetujui</strong>.
                                Anda sekarang dapat login dan mengakses semua fitur yang tersedia.
                            </p>

                            <div style="text-align: center; margin: 30px 0;">
                                <a href="{{ route('login') }}"
                                   style="display: inline-block; background-color: #4a7c2c; color: #ffffff; padding: 14px 40px; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 16px;">
                                    Login Sekarang
                                </a>
                            </div>

                            <p style="color: #6b7280; font-size: 14px; line-height: 1.6; margin-top: 30px;">
                                Jika tombol tidak berfungsi, salin dan paste link berikut di browser Anda:<br>
                                <a href="{{ route('login') }}" style="color: #4a7c2c; word-break: break-all;">{{ route('login') }}</a>
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background-color: #f9fafb; padding: 30px; text-align: center; border-top: 1px solid #e5e7eb;">
                            <p style="color: #6b7280; font-size: 14px; margin: 0;">
                                © {{ date('Y') }} SALAKITA. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
