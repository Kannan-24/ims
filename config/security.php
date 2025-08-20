<?php

return [
    // Minimum number of consecutive failed logins before sending an alert email.
    // Default now 1 so you get alerted on the first failed attempt for an existing user.
    'failed_login_alert_threshold' => env('FAILED_LOGIN_ALERT_THRESHOLD', 1),

    // When true, once the threshold is reached an alert is sent for EVERY subsequent failure.
    // With threshold=1 + every=true you get an email for each failed attempt.
    'failed_login_alert_every' => env('FAILED_LOGIN_ALERT_EVERY', true),
];
