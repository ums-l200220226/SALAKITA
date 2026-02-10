<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Ditolak</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f3f4f6;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f3f4f6; padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">

                    {{-- Header --}}
                    <tr>
                        <td style="background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%); padding: 40px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 28px;">SALAKITA</h1>
                        </td>
                    </tr>

                    {{-- Content --}}
                    <tr>
                        <td style="padding: 40px;">
                            <div style="text-align: center; margin-bottom: 30px;">
                                <div style="display: inline-block; width: 80px; height: 80px; background-color: #fee2e2; border-radius: 50%; line-height: 80px;">
                                    <span style="color: #dc2626; font-size: 40px;">✕</span>
                                </div>
                            </div>

                            <h2 style="color: #991b1b; text-align: center; margin-bottom: 20px;">Pendaftaran Anda Ditolak</h2>

                            <p style="color: #4b5563; line-height: 1.6; margin-bottom: 20px;">
                                Halo <strong>{{ $petani->name }}</strong>,
                            </p>

                            <p style="color: #4b5563; line-height: 1.6; margin-bottom: 20px;">
                                Mohon maaf, pendaftaran Anda sebagai petani di platform SALAKITA <strong style="color: #dc2626;">ditolak</strong>.
                            </p>

                            <div style="background-color: #fef2f2; border-left: 4px solid #dc2626; padding: 15px; margin: 20px 0; border-radius: 4px;">
                                <p style="color: #991b1b; margin: 0; font-weight: bold; margin-bottom: 8px;">Alasan Penolakan:</p>
                                <p style="color: #4b5563; margin: 0; line-height: 1.6;">{{ $reason }}</p>
                            </div>

                            <p style="color: #4b5563; line-height: 1.6; margin-bottom: 20px;">
                                Anda dapat memperbaiki data dan mendaftar kembali dengan mengklik tombol di bawah ini.
                            </p>

                            <div style="text-align: center; margin: 30px 0;">
                                <a href="{{ route('register') }}"
                                   style="display: inline-block; background-color: #4a7c2c; color: #ffffff; padding: 14px 40px; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 16px;">
                                    Daftar Ulang
                                </a>
                            </div>

                            <p style="color: #6b7280; font-size: 14px; line-height: 1.6; margin-top: 30px;">
                                Jika tombol tidak berfungung, salin dan paste link berikut di browser Anda:<br>
                                <a href="{{ route('register') }}" style="color: #4a7c2c; word-break: break-all;">{{ route('register') }}</a>
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
