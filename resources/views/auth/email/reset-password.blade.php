<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
</head>
<body style="
    margin:0;
    padding:0;
    background-color:#f3f4f6;
    font-family:Arial, Helvetica, sans-serif;
">

<table role="presentation" style="
    width:100%;
    border-collapse:collapse;
    padding:30px 0;
">
    <tr>
        <td style="text-align:center;">

            <!-- CARD -->
            <table role="presentation" style="
                width:100%;
                max-width:600px;
                margin:0 auto;
                background-color:#ffffff;
                border-radius:16px;
                box-shadow:0 10px 25px rgba(0,0,0,0.08);
                overflow:hidden;
                border-collapse:collapse;
            ">

                <!-- HEADER -->
                <thead>
                    <tr>
                        <th scope="col" style="
                            background-color:#ffffff;
                            padding:30px;
                            text-align:center;
                            color:#166534;
                        ">
                            <img src="{{ $logo }}" alt="Logo Masjid"
                                 style="max-width:120px; margin-bottom:12px; display:block; margin-left:auto; margin-right:auto;">
                            <div style="font-size:22px; font-weight:bold;">
                                Reset Password
                            </div>
                            <div style="font-size:14px; opacity:0.9; margin-top:4px;">
                                Masjid Baiturrahman
                            </div>
                        </th>
                    </tr>
                </thead>

                <!-- BODY -->
                <tbody>
                    <tr>
                        <td style="
                            padding:32px;
                            color:#374151;
                            font-size:15px;
                            line-height:1.6;
                        ">
                            <p>
                                Assalamu’alaikum <strong>{{ $user->name }}</strong>,
                            </p>

                            <p>
                                Kami menerima permintaan untuk mereset password akun Anda.
                                Silakan klik tombol di bawah ini untuk melanjutkan.
                            </p>

                            <!-- BUTTON -->
                            <div style="text-align:center; margin:32px 0;">
                                <a href="{{ $resetUrl }}" style="
                                    display:inline-block;
                                    background-color:#166534;
                                    color:#ffffff;
                                    padding:14px 32px;
                                    border-radius:10px;
                                    text-decoration:none;
                                    font-weight:bold;
                                    font-size:15px;
                                ">
                                    Reset Password
                                </a>
                            </div>

                            <p style="font-size:13px; color:#6b7280;">
                                Link ini berlaku selama <strong>60 menit</strong>.
                            </p>

                            <p style="font-size:13px; color:#6b7280;">
                                Jika Anda tidak merasa melakukan permintaan ini,
                                silakan abaikan email ini.
                            </p>

                            <p style="margin-top:28px;">
                                Wassalamu’alaikum,<br>
                                <strong>Masjid Baiturrahman</strong>
                            </p>
                        </td>
                    </tr>
                </tbody>

                <!-- FOOTER -->
                <tfoot>
                    <tr>
                        <td style="
                            background-color:#f9fafb;
                            padding:18px;
                            text-align:center;
                            font-size:12px;
                            color:#9ca3af;
                        ">
                            © {{ date('Y') }} Masjid Baiturrahman<br>
                            Email ini dikirim secara otomatis, mohon tidak membalas.
                        </td>
                    </tr>
                </tfoot>

            </table>

        </td>
    </tr>
</table>

</body>
</html>
