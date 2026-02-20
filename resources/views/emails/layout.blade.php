<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f9;
            color: #374151;
        }

        .wrapper {
            padding: 40px 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid #eef2f5;
        }

        .header {
            background:
                {{ $brand_color ?? '#a82244' }}
            ;
            padding: 30px;
            text-align: center;
        }

        .logo {
            max-height: 50px;
            width: auto;
            filter: brightness(0) invert(1);
        }

        .logo-text {
            color: #ffffff;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -1px;
        }

        .body {
            padding: 40px;
            line-height: 1.6;
            font-size: 15px;
        }

        .footer {
            background: #f8fafc;
            padding: 25px;
            text-align: center;
            color: #94a3b8;
            font-size: 11px;
            border-top: 1px solid #edf2f7;
            line-height: 1.5;
        }

        .footer a {
            color:
                {{ $brand_color ?? '#a82244' }}
            ;
            text-decoration: none;
            font-weight: 600;
        }

        h1,
        h2,
        h3 {
            color: #1e293b;
            margin-top: 0;
        }

        p {
            margin-bottom: 20px;
        }
    </style>
</head>

<body style="margin: 0; padding: 0; background-color: #f4f7f9;">
    <div class="wrapper" style="padding: 40px 10px;">
        <div class="container"
            style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid #eef2f5;">
            <div class="header"
                style="background: {{ $brand_color ?? '#dc2626' }}; padding: 30px; text-align: center; color: #ffffff;">
                @if(!empty($brand_logo))
                    <img src="{{ $brand_logo }}" alt="{{ $brand_name ?? 'MIONEX' }}"
                        style="max-height: 50px; width: auto; filter: brightness(0) invert(1);">
                @else
                    <div style="font-size: 24px; font-weight: 800; letter-spacing: -1px;">{{ $brand_name ?? 'MIONEX' }}
                    </div>
                @endif
            </div>

            <div class="body" style="padding: 40px; line-height: 1.6; color: #334455; font-size: 15px;">
                @yield('content')
            </div>

            <div class="footer"
                style="background: #f8fafc; padding: 25px; text-align: center; color: #94a3b8; font-size: 11px; border-top: 1px solid #edf2f7; line-height: 1.5;">
                <p style="margin: 0 0 10px 0;">Bu bir sistem bildirimidir. Lütfen bu e-postayı yanıtlamayın.</p>
                <p style="margin: 0;">&copy; {{ date('Y') }} <strong>{{ $brand_name ?? 'MIONEX' }}</strong>. Tüm hakları
                    saklıdır.</p>
            </div>
        </div>
    </div>
</body>

</html>