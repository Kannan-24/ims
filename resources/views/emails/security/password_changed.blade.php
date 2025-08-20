<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Changed</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #fff; color: #202124;">
    <div style="max-width: 600px;">
        <div style="text-align: left; margin-bottom: 15px;"><img src="{{ asset('assets/logo.png') }}" alt="{{ config('company.name') }}" style="height:40px;"></div>
        <h2 style="font-size:18px; margin:0 0 12px;">Hi {{ $user->name }},</h2>
        <p style="font-size:14px; margin:0 0 12px; line-height:1.5;">Your account password was changed on <strong>{{ $time }} ({{ config('app.timezone') }})</strong>.</p>
        <table style="width:100%; margin-top:10px; font-size:14px; border-collapse:collapse;">
            <tr><td style="padding:6px 12px; font-weight:bold;">IP Address</td><td style="padding:6px 12px;">{{ $ip }}</td></tr>
            <tr><td style="padding:6px 12px; font-weight:bold;">Device / Browser</td><td style="padding:6px 12px;">{{ $agent }}</td></tr>
        </table>
        <p style="font-size:14px; margin:16px 0 12px;">If you did not perform this change, immediately <a href="{{ route('password.request') }}" style="color:#1a73e8; text-decoration:none;">reset your password</a> and review active sessions on your <a href="{{ route('account.settings') }}" style="color:#1a73e8; text-decoration:none;">Sessions Page</a>.</p>
        <p style="font-size:14px; margin:0 0 10px;">For assistance contact <a href="mailto:{{ config('company.email') }}" style="color:#1a73e8; text-decoration:none;">{{ config('company.email') }}</a>.</p>
        <p style="font-size:14px; margin:0 0 5px;">Regards,</p>
        <p style="font-size:14px; font-weight:bold; margin:0 0 20px;">{{ config('company.name') }} Security Team</p>
    <hr style="border:none; border-top:1px solid #e0e0e0; margin:20px 0;">
    @php $abuseHost = parse_url(config('company.website') ?? '', PHP_URL_HOST) ?: str_replace(['http://','https://'],'',config('company.website') ?? ''); @endphp
    <p style="font-size:12px; color:#777; line-height:1.5; margin:0;">If you didn't request this, please report it to <a href="mailto:abuse@{{ $abuseHost }}" style="color:#1a73e8; text-decoration:none;">abuse@{{ $abuseHost }}</a>.</p>
    </div>
</body>
</html>
