<?php

return [
    'table_prefix' => env('FAKTURA_TABLE_PREFIX', 'faktura_'),
    'stripe' => [
        'webhook_secret' => env('FAKTURA_STRIPE_WEBHOOK_SECRET'),
    ],
];
