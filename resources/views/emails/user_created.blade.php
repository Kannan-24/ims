<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to {{ config('company.name') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #fff;
            color: #202124
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 24px
        }

        .logo {
            height: 40px;
            margin-bottom: 14px
        }

        .h {
            font-size: 20px;
            margin: 0 0 12px
        }

        .muted {
            color: #555;
            font-size: 14px
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

        .footer {
            margin-top: 20px;
            color: #777;
            font-size: 12px
        }
    </style>
</head>

<body>
    <div class="container">
        <img src="{{ asset('assets/logo.png') }}" alt="{{ config('company.name') }}" class="logo">
        <h2 class="h">Welcome, {{ $user->name }}!</h2>
        <p class="muted">Your account has been created. Below are the credentials and next steps.</p>

        <table style="width:100%;font-size:14px;margin-top:12px">
            <tr>
                <td style="padding:6px 0;font-weight:600">Employee ID</td>
                <td style="padding:6px 0">{{ $user->employee_id }}</td>
            </tr>
            <tr>
                <td style="padding:6px 0;font-weight:600">Phone</td>
                <td style="padding:6px 0">{{ $user->phone }}</td>
            </tr>
            <tr>
                <td style="padding:6px 0;font-weight:600">Email</td>
                <td style="padding:6px 0">{{ $user->email }}</td>
            </tr>
            <tr>
                <td style="padding:6px 0;font-weight:600">Temporary Password</td>
                <td style="padding:6px 0">{{ $defaultPassword }}</td>
            </tr>
        </table>

        <p style="margin:18px 0 0">You will be required to change this temporary password on first login. Passwords
            expire every {{ config('password_policy.expiry_days') }} days.</p>

        <p style="margin:18px 0"><a href="{{ route('login') }}" class="btn">Log in to
                {{ config('company.name') }}</a></p>

        <div class="footer">Regards,<br><strong>{{ config('company.name') }} Admin Team</strong></div>
    </div>
</body>

</html>
