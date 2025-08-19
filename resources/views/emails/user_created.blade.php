<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to SKM and Company</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9fafb;
            margin: 0;
            padding: 0;
            color: #333;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            color: #0056b3;
            font-size: 26px;
            font-weight: bold;
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 18px;
            margin-bottom: 10px;
            color: #444;
            font-weight: bold;
        }
        .info {
            margin-bottom: 20px;
        }
        .info p {
            margin: 10px 0;
        }
        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 20px;
            background-color: #0056b3;
            color: #ffffff;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            border-radius: 6px;
            text-align: center;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #003d80;
        }
        .footer {
            margin-top: 30px;
            font-size: 14px;
            text-align: center;
            color: #6c757d;
            border-top: 1px solid #e9ecef;
            padding-top: 15px;
        }
        .highlight {
            color: #0056b3;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="header">Welcome to SKM and Company, <span class="highlight">{{ $user->name }}</span>!</h2>

        <div class="info">
            <p class="section-title">We are excited to inform you that your account has been successfully created.</p>

            <p><strong>Employee ID:</strong> <span class="highlight">{{ $user->employee_id }}</span></p>
            <p><strong>Phone Number:</strong> <span class="highlight">{{ $user->phone }}</span></p>

            <p><strong>Login Details:</strong> Please use your email address and the default password provided below to access your account.</p>
            <p><strong>Email Address:</strong> <span class="highlight">{{ $user->email }}</span></p>
            <p><strong>Temporary Password:</strong> <span class="highlight">{{ $defaultPassword }}</span></p>
        </div>

        <p>You can access the system by clicking the link below:</p>
        <a href="{{ env('APP_URL') }}" class="btn">Log in to SKM and Company</a>

    <p style="margin-top: 20px;">For your security, you will be <strong>required</strong> to change this temporary password on first login. Your password will then expire every {{ config('password_policy.expiry_days') }} days and you'll receive reminder emails before it expires.</p>

        <div class="footer">
            Regards,<br>
            <strong>SKM and Company Admin Team</strong>
        </div>
    </div>
</body>
</html>
