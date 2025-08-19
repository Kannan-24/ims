<?php

return [
    'expiry_days' => env('PASSWORD_EXPIRY_DAYS', 90),
    'reminder_offsets' => [14, 7, 3, 1],
    'min_length' => 12,
    'require_uppercase' => true,
    'require_lowercase' => true,
    'require_number' => true,
    'require_symbol' => true,
];
