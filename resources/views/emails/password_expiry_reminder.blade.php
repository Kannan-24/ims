<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Password Expiry Reminder</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #202124
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 24px
        }

        .btn {
            display: inline-block;
            background: #1a73e8;
            color: #fff;
            padding: 10px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600
        }

        .muted {
            color: #555
        }
    </style>
</head>

<body>
    <div class="container">
        <img src="{{ asset('assets/logo.png') }}" alt="{{ config('company.name') }}"
            style="height:40px;margin-bottom:12px">
        <h2 style="margin:0 0 10px">Password Expiry Reminder</h2>
        <p class="muted">Hello {{ $user->name }},</p>
        <p>Your password will expire in <strong>{{ $daysLeft }}</strong> day{{ $daysLeft == 1 ? '' : 's' }}. Please
            update it to avoid losing access.</p>
        <p style="margin:16px 0"><a href="{{ route('password.confirm') }}" class="btn">Update Password</a></p>
        <p class="muted" style="margin-top:18px">Regards,<br>{{ config('company.name') }} Support Team</p>
    </div>
</body>

</html>
