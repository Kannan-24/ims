<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Two‑Factor Authentication Code</title>
    <style>body{font-family:Arial,sans-serif;margin:0;padding:0;color:#202124}.container{max-width:600px;margin:20px auto;padding:24px}.logo{height:40px;margin-bottom:14px}.code{font-family:monospace;background:#f1f5f9;padding:10px 14px;border-radius:6px;font-size:20px;display:inline-block}</style>
</head>
<body>
<div class="container">
    <img src="{{ asset('assets/logo.png') }}" alt="{{ config('company.name') }}" class="logo">
    <h2 style="font-size:18px;margin:0 0 12px">Your {{ $purpose ?? 'verification' }} code</h2>
    <p style="margin:0 0 12px;font-size:14px;">Hi {{ $user->name }},</p>
    <p style="font-size:14px;margin:0 0 12px;">Use the following one‑time code to {{ $purpose ?? 'verify' }} your account action:</p>
    <p style="margin:8px 0 12px;"><span class="code">{{ $code }}</span></p>
    <p style="font-size:13px;margin:0 0 12px;">This code expires in <strong>{{ $otp_ttl ?? 10 }}</strong> minutes (until {{ $otp_expires_at ?? '' }}). It was requested from IP <strong>{{ $ip ?? request()->ip() }}</strong> using <strong>{{ $agent ?? 'Unknown' }}</strong>.</p>
    <p style="font-size:13px;margin:0 0 12px;">Location: <strong>{{ $location ?? 'Location not available' }}</strong></p>
    <p style="font-size:14px;margin:16px 0 0;">If you did not request this code, ignore this email or contact <a href="mailto:{{ config('company.email') }}">{{ config('company.email') }}</a>.</p>
    <hr style="border:none;border-top:1px solid #e0e0e0;margin:20px 0">
    @php $abuseHost = parse_url(config('company.website') ?? '', PHP_URL_HOST) ?: str_replace(['http://','https://'],'',config('company.website') ?? ''); @endphp
    <p style="font-size:12px;color:#777">{{ config('company.name') }}, {{ config('company.address') }} — Report abuse: <a href="mailto:abuse@{{ $abuseHost }}">abuse@{{ $abuseHost }}</a></p>
</div>
</body>
</html>
