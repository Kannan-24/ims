<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Alert – Failed Sign-Ins</title>
</head>

<body style="font-family: Arial, sans-serif; margin:0; padding:0; background:#fff; color:#202124;">
    <div style="max-width:600px;">
        <div style="text-align:left; margin-bottom:15px;">
            <img src="{{ asset('assets/logo.png') }}" alt="{{ config('company.name') }}" style="height:40px;">
        </div>
        <h2 style="font-size:18px; margin:0 0 12px; color:#b91c1c;">Security Alert – Repeated Failed Sign‑Ins</h2>
        <p style="font-size:14px; margin:0 0 12px;">Hi {{ $user->name }},</p>
        <p style="font-size:14px; margin:0 0 12px; line-height:1.5;">We detected <strong>{{ $attempts }}</strong>
            consecutive unsuccessful login attempts to your account. Review the details below and secure your account if
            necessary.</p>
        <table style="width:100%; margin-top:10px; font-size:14px; border-collapse:collapse;">
            <tr>
                <td style="padding:6px 12px; font-weight:bold;">Last Attempt</td>
                <td style="padding:6px 12px;">{{ $time }} ({{ config('app.timezone') }})</td>
            </tr>
            <tr>
                <td style="padding:6px 12px; font-weight:bold;">IP Address</td>
                <td style="padding:6px 12px;">{{ $ip }}</td>
            </tr>
            <tr>
                <td style="padding:6px 12px; font-weight:bold;">Device / Browser</td>
                <td style="padding:6px 12px;">{{ $agent }}</td>
            </tr>
        </table>
        <p style="font-size:14px; margin:16px 0 12px; line-height:1.5;">If these attempts weren't made by you, reset
            your password and enable Two‑Factor Authentication immediately.</p>
        <p style="margin:0 0 20px;">
            <a href="{{ route('password.request') }}"
                style="background:#d32f2f; color:#fff; padding:10px 18px; font-size:14px; text-decoration:none; border-radius:4px; display:inline-block;">Reset
                Password</a>
            <a href="{{ route('account.settings') }}"
                style="margin-left:8px; background:#1a73e8; color:#fff; padding:10px 18px; font-size:14px; text-decoration:none; border-radius:4px; display:inline-block;">Manage
                Security</a>
        </p>
        <h3 style="font-size:15px; margin:0 0 8px; color:#0f172a;">Security Tips</h3>
        <ul style="margin:0 0 20px; padding-left:18px; font-size:13px; line-height:1.5; color:#334155;">
            <li>Use a strong, unique password.</li>
            <li>Enable Two‑Factor Authentication.</li>
            <li>Never share your credentials.</li>
            <li>Contact support if suspicious activity continues.</li>
        </ul>
        <p style="font-size:14px; margin:0 0 5px;">Regards,</p>
        <p style="font-size:14px; font-weight:bold; margin:0 0 20px;">{{ config('company.name') }} Security Team</p>
        <hr style="border:none; border-top:1px solid #e0e0e0; margin:20px 0;">
        <p style="font-size:12px; color:#777; line-height:1.5; margin:0;">If you mistyped your password you can ignore
            this email. If these attempts weren't you, reset your password and enable Two‑Factor Authentication
            immediately.</p>
        @php $abuseHost = parse_url(config('company.website') ?? '', PHP_URL_HOST) ?: str_replace(['http://','https://'],'',config('company.website') ?? ''); @endphp
        <p style="font-size:12px; color:#777; line-height:1.5; margin:0;">If you mistyped your password you can ignore
            this email. If these attempts weren't you, reset your password and enable Two‑Factor Authentication
            immediately. If you'd like to report abuse contact <a
                href="mailto:abuse@{{ $abuseHost }}">abuse@{{ $abuseHost }}</a>.</p>
    </div>
</body>

</html>
