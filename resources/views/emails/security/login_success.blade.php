<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Successful</title>
</head>

<body style="font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #fff; color: #202124;">
    <div style="max-width: 600px;">

        <!-- Company Logo -->
        <div style="text-align: left; margin-bottom: 15px;">
            <img src="{{ asset('assets/logo.png') }}" alt="{{ config('company.name') }}" style="height: 40px;">
        </div>

        <!-- Heading -->
        <h2 style="font-size: 18px; margin: 0 0 12px; color: #202124;">
            Hi {{ $user->name }},
        </h2>

        <!-- Intro -->
        <p style="font-size: 14px; margin: 0 0 12px; line-height: 1.5;">
            There's been a new sign-in to your {{ config('company.name') }} account
            <strong>{{ $user->email }}</strong> on
            <strong>{{ $time }} ({{ config('app.timezone') }})</strong>.
        </p>

        <!-- Login Details -->
        <table style="width:100%; margin-top: 10px; font-size: 14px; border-collapse: collapse;">
            <tr>
                <td style="padding: 6px 12px; font-weight: bold;">Device</td>
                <td style="padding: 6px 12px;">{{ $device ?? 'Unknown Device' }}</td>
            </tr>
            <tr>
                <td style="padding: 6px 12px; font-weight: bold;">App / Browser</td>
                <td style="padding: 6px 12px;">{{ $agent }}</td>
            </tr>
            <tr>
                <td style="padding: 6px 12px; font-weight: bold;">IP Address</td>
                <td style="padding: 6px 12px;">{{ $ip }}</td>
            </tr>
        </table>

        <!-- Security Notice -->
        <p style="font-size: 14px; margin: 16px 0 10px; line-height: 1.5;">
            If this wasn't you, you need to
            <a href="{{ route('password.request') }}" style="color: #1a73e8; text-decoration: none;">
                change your {{ config('company.name') }} account password
            </a>
            to protect your account.
        </p>

        <!-- Session Link -->
        <p style="font-size: 14px; margin: 0 0 10px;">
            To view your sign-in history and active sessions, refer to your
            <a href="{{ route('account.settings') }}" style="color: #1a73e8; text-decoration: none;">
                Sessions Page
            </a>.
        </p>

        <!-- Support Info -->
        <p style="font-size: 14px; margin: 0 0 10px;">
            For assistance, contact us at
            <a href="mailto:{{ config('company.email') }}" style="color: #1a73e8; text-decoration: none;">
                {{ config('company.email') }}
            </a>.
        </p>

        <!-- Extra Security Tips -->
        <p style="font-size: 14px; margin: 0 0 20px;">
            Learn more on
            <a href="{{ config('company.website') }}/security-tips" style="color: #1a73e8; text-decoration: none;">
                ways to protect your {{ config('company.name') }} account here
            </a>.
        </p>

        <!-- Signature -->
        <p style="font-size: 14px; margin: 0 0 5px;">Regards,</p>
        <p style="font-size: 14px; font-weight: bold; margin: 0 0 5px;">{{ config('company.name') }} Security Team</p>
        <p style="font-size: 14px; margin: 0 0 20px;">
            <a href="{{ config('company.website') }}" style="color: #1a73e8; text-decoration: none;">
                {{ config('company.website') }}
            </a>
        </p>

        <!-- Footer -->
        <hr style="border: none; border-top: 1px solid #e0e0e0; margin: 20px 0;">
        <p style="font-size: 12px; color: #777; line-height: 1.5; margin: 0;">
            {{ config('company.name') }}, {{ config('company.address') }} <br>
            Phone: {{ config('company.phone') }} | {{ config('company.phone_alt') }} <br><br>
            This email was generated automatically by {{ config('company.name') }}.
            If you think this is spam, please report it to
            <a href="mailto:{{ config('company.abuse_email') }}" style="color: #1a73e8; text-decoration: none;">
                {{ config('company.abuse_email') }}
            </a>.
        </p>
    </div>
</body>

</html>
