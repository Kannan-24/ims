<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Password Expiry Reminder</title></head>
<body>
    <p>Dear {{ $user->name }},</p>
    <p>Your account password will expire in <strong>{{ $daysLeft }} day{{ $daysLeft === 1 ? '' : 's' }}</strong>.</p>
    <p>Please log in and update your password to avoid interruption.</p>
    <p>If you've recently changed it, you can ignore this message.</p>
    <p>Regards,<br>Support Team</p>
</body>
</html>
