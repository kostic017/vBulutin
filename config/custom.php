<?php

return [
    'pagination' => [
        'step' => env('PAGINATION_STEP', 5),
        'max' => env('PAGINATION_MAX', 100),
    ],

    'captcha' => [
        'site_key' => env('CAPTCHA_SITE_KEY'),
        'secret_key' => env('CAPTCHA_SECRET_KEY'),
    ],

    'gc_read_status_days' => env('GC_READ_STATUS_DAYS', 30),
    'refresh_online_minutes' => env('REFRESH_ONLINE_MINUTES', 5),
];
