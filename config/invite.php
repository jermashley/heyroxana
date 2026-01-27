<?php

return [
    'expected_token' => env('EXPECTED_TOKEN'),
    'invitee_name' => env('INVITEE_NAME', 'Roxana'),
    'available_dates' => array_values(array_filter(array_map('trim', explode(',', env('INVITE_AVAILABLE_DATES', ''))))),
    'evening_time' => env('INVITE_EVENING_TIME', '19:00'),
    'mail_to_address' => env('MAIL_TO_ADDRESS'),
];
