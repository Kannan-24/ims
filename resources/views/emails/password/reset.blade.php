<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
</head>

<body style="font-family:Arial, sans-serif; margin:0; padding:0; background:#fff; color:#202124;">
    <div style="max-width:600px;">
        <div style="text-align:left; margin-bottom:15px;">
            <img src="{{ asset('assets/logo.png') }}" alt="{{ config('company.name') }}" style="height:40px;">
        </div>
        <h2 style="font-size:20px; margin:0 0 14px; color:#202124;">Reset Your Password</h2>
        <p style="font-size:14px; margin:0 0 12px; line-height:1.5;">We received a request to reset the password for
            your {{ config('company.name') }} account. Click the button below to set a new password.</p>
        <p style="margin:0 0 20px;">
            <a href="{{ $resetUrl }}"
                style="background:#1a73e8; color:#fff; padding:12px 20px; font-size:14px; text-decoration:none; border-radius:4px; display:inline-block;">Choose
                New Password</a>
        </p>
    @if(!empty($otp))
    <p style="font-size:15px; margin:8px 0 4px; font-weight:700;">One-time password (OTP): <span style="font-family:monospace;background:#f1f5f9;padding:6px 8px;border-radius:4px;color:#0f172a;">{{ $otp }}</span></p>
    <p style="font-size:13px; margin:0 0 12px; line-height:1.5;">This OTP is valid for {{ $otp_ttl }} minutes until <strong>{{ $otp_expires_at }}</strong>. Use it to verify your identity and then set a new password using the button above.</p>
    @else
    <p style="font-size:13px; margin:0 0 12px; line-height:1.5;">This link will expire in {{ $count }} minutes. If you did not request a password reset, you can safely ignore this email; your password will remain unchanged.</p>
    @endif
        <h3 style="font-size:15px; margin:24px 0 8px; color:#0f172a;">Security Tips</h3>
        <ul style="margin:0 0 20px; padding-left:18px; font-size:13px; line-height:1.5; color:#334155;">
            <li>Use a strong, unique password you don’t reuse elsewhere.</li>
            <li>Enable Two‑Factor Authentication for added protection.</li>
            <li>Never share reset links or passwords.</li>
        </ul>
        <p style="font-size:14px; margin:0 0 5px;">Regards,</p>
        <p style="font-size:14px; font-weight:bold; margin:0 0 20px;">{{ config('company.name') }} Support Team</p>
        <hr style="border:none; border-top:1px solid #e0e0e0; margin:20px 0;">
        <p style="font-size:12px; color:#777; line-height:1.5; margin:0;">If you didn't request this, you can ignore it.
            Someone might have typed your email by mistake.</p>
    </div>
</body>

</html>
