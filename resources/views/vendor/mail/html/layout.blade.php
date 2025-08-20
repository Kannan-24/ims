@php($appName = config('app.name'))
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>{{ $appName }} Mail</title>
    <style>
        body {
            background: #0f172a;
            color: #334155;
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        .wrapper {
            width: 100%;
            background: #0f172a;
            padding: 20px 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 16px -2px rgba(0, 0, 0, .25);
        }

        .header {
            background: #1e293b;
            padding: 20px;
            text-align: center;
        }

        .logo {
            font-size: 20px;
            font-weight: 600;
            color: #fff;
            text-decoration: none;
            letter-spacing: .5px;
        }

        .hero {
            padding: 24px 32px;
            font-size: 14px;
            line-height: 1.6;
            color: #0f172a;
        }

        .btn {
            display: inline-block;
            background: #2563eb;
            color: #fff !important;
            text-decoration: none;
            padding: 12px 22px;
            border-radius: 6px;
            font-weight: 600;
            margin: 18px 0;
        }

        .btn:hover {
            background: #1d4ed8;
        }

        .code {
            font-family: monospace;
            background: #f1f5f9;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 13px;
        }

        .footer {
            padding: 24px 32px;
            background: #f8fafc;
            font-size: 11px;
            line-height: 1.5;
            color: #475569;
            text-align: center;
        }

        @media (max-width:620px) {

            .hero,
            .footer {
                padding: 20px 20px;
            }

            .btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container">
            <div class="header"><a class="logo" href="{{ config('app.url') }}">{{ $appName }}</a></div>
            <div class="hero">{{ $slot }}</div>
            <div class="footer">
                <p style="margin:4px 0">Need help? Contact <a href="mailto:support@{{ parse_url(config('app.url'), PHP_URL_HOST) ?? 'example.com' }}"
                        style="color:#2563eb;text-decoration:none">support</a>.</p>
                <p style="margin:4px 0">If you did not initiate this action, please secure your account immediately.</p>
                <p style="margin:8px 0 0 0">&copy; {{ date('Y') }} {{ $appName }}. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>

</html>
