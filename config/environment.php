<?php

return [
    'stripe' => [
        'STRIPE_PUBLISHABLE_KEY' => env('STRIPE_PUBLISHABLE_KEY'),
        'STRIPE_SECRET_KEY' => env('STRIPE_SECRET_KEY'),
        'STRIPE_WEBHOOK_SECRET' => env('STRIPE_WEBHOOK_SECRET'),
        'CURRENCY' => env('CURRENCY'),
    ],
];
