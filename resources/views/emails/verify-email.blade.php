<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
            color: #333;
            line-height: 1.6;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .email-header {
            padding: 30px 20px;
            text-align: center;
            background-color: #f5f9f7;
            position: relative;
            overflow: hidden;
        }

        .logo {
            max-width: 180px;
            margin-bottom: 10px;
            position: relative;
            z-index: 2;
        }

        .email-body {
            padding: 30px;
            position: relative;
        }

        .greeting {
            font-size: 24px;
            font-weight: bold;
            color: #1e7a4a;
            margin-bottom: 20px;
        }

        .content {
            margin-bottom: 30px;
        }

        .button {
            display: inline-block;
            background-color: #1e7a4a;
            color: white !important;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #16633c;
        }

        .email-footer {
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 14px;
            background-color: #f5f9f7;
            border-top: 1px solid #eaeaea;
        }

        .subcopy {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eaeaea;
            color: #666;
            font-size: 13px;
        }

        /* Decorative elements */
        .decoration-1 {
            position: absolute;
            width: 60px;
            height: 60px;
            background-color: rgba(30, 122, 74, 0.15);
            border-radius: 50%;
            top: 15px;
            left: 15px;
            z-index: 1;
        }

        .decoration-2 {
            position: absolute;
            width: 100px;
            height: 100px;
            background-color: rgba(30, 122, 74, 0.1);
            border-radius: 50%;
            bottom: -50px;
            right: -20px;
            z-index: 1;
        }

        .decoration-3 {
            position: absolute;
            width: 40px;
            height: 40px;
            background-color: rgba(30, 122, 74, 0.15);
            border-radius: 50%;
            bottom: 40px;
            left: -20px;
            z-index: 1;
        }

        .decoration-4 {
            position: absolute;
            width: 20px;
            height: 80px;
            background-color: rgba(30, 122, 74, 0.1);
            border-radius: 20px;
            top: 60px;
            right: 30px;
            transform: rotate(-30deg);
            z-index: 1;
        }

        @media only screen and (max-width: 600px) {
            .email-container {
                border-radius: 0;
            }

            .email-body {
                padding: 20px;
            }

            .greeting {
                font-size: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">

        <!-- Header with logo -->
        <div class="email-header">

            <!-- Main content -->
            <div class="email-body">
                <div class="greeting">Halo {{ $notifiable->name }}!</div>

                <div class="content">
                    <p>Terima kasih telah mendaftar di website <strong>Laboratorium Sinyal Digital dan Arsitektur
                            Komputer
                            (Digikom)</strong>.</p>
                    <p>Silakan klik tombol di bawah untuk memverifikasi alamat email Anda dan melengkapi proses
                        pendaftaran.
                    </p>

                    <div style="text-align: center;">
                        <a href="{{ $url }}" class="button">Verifikasi Email Saya</a>
                    </div>

                    <p>Jika Anda tidak membuat akun, abaikan email ini.</p>
                </div>

                <div style="margin-top: 30px;">
                    <p>Salam hangat,<br>Tim Website Laboratorium Digikom</p>
                </div>

                <!-- Subcopy with alternative link -->
                <div class="subcopy">
                    <p>Jika Anda mengalami masalah saat mengklik tombol "Verifikasi Email Saya", salin dan tempel URL di
                        bawah ke dalam browser web Anda:</p>
                    <p style="word-break: break-all;"><a href="{{ $url }}"
                            style="color: #1e7a4a;">{{ $url }}</a></p>
                </div>
            </div>

            <!-- Footer -->
            <div class="email-footer">
                <p>&copy; {{ date('Y') }} Laboratorium Sinyal Digital dan Arsitektur Komputer. Semua hak dilindungi.
                </p>
            </div>
        </div>
</body>

</html>
