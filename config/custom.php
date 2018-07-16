<?php

return [
    'domain' => env('APP_DOMAIN'),
    'subdomains' => env('APP_SUBDOMAINS'),

    'pagination' => [
        'step' => env('PAGINATION_STEP'),
        'max' => env('PAGINATION_MAX'),
    ],

    'captcha' => [
        'site_key' => env('CAPTCHA_SITE_KEY'),
        'secret_key' => env('CAPTCHA_SECRET_KEY'),
    ],

    'random_image' => env('RANDOM_IMAGE', null),
    'recent_topic_days' => env('RECENT_TOPIC_DAYS'),
    'refresh_online_minutes' => env('REFRESH_ONLINE_MINUTES'),
];
