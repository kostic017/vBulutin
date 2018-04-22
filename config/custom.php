<?php

return [
    'pagination' => [
        'step' => env('PAGINATION_STEP', 5),
        'max' => env('PAGINATION_MAX', 100)
    ],

    'gc' => [
        'read_status' => env('GC_READ_STATUS', 30)
    ]
];
