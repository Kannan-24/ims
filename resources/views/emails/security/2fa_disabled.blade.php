<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Two‑Factor Authentication Disabled</title>
</head>
<body style="font-family:Arial,sans-serif;margin:0;padding:0;color:#202124">
<div style="max-width:600px;margin:20px auto;padding:24px">
    <img src="{{ asset('assets/logo.png') }}" alt="{{ config('company.name') }}" style="height:40px;margin-bottom:12px">
    <h2 style="font-size:18px;margin:0 0 12px">Two‑Factor Authentication disabled</h2>
    <p style="margin:0 0 12px">Hi {{ $user->name }},</p>
    <p style="margin:0 0 12px">Two‑Factor Authentication (<strong>{{ $method ?? 'all methods' }}</strong>) was disabled for your account on <strong>{{ $time }}</strong>. The change was requested from IP <strong>{{ $ip ?? request()->ip() }}</strong> using <strong>{{ $agent ?? 'Unknown' }}</strong>.</p>
    <p style="margin:0 0 12px">If you did not make this change, immediately <a href="{{ route('password.request') }}" style="color:#1a73e8">reset your password</a> and contact support at <a href="mailto:{{ config('company.email') }}">{{ config('company.email') }}</a>.</p>
    <p style="margin:0 0 12px">Regards,<br><strong>{{ config('company.name') }} Security Team</strong></p>
</div>
</body>
</html>
