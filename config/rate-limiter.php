<?php

return [
    'max-attempts' => [
        'auth' => env('RATE_LIMITER_MAX_ATTEMPTS_AUTH', 300),
        'ip' => env('RATE_LIMITER_MAX_ATTEMPTS_IP', 100),
    ]
];
