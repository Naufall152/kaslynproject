<?php

return [
    // Wajib
    'merchant_id' => env('MIDTRANS_MERCHANT_ID'),
    'client_key'  => env('MIDTRANS_CLIENT_KEY'),
    'server_key'  => env('MIDTRANS_SERVER_KEY'),

    // Environment
    'is_production' => (bool) env('MIDTRANS_IS_PRODUCTION', false),

    // Best practice Midtrans
    'sanitize' => (bool) env('MIDTRANS_SANITIZE', true),
    'is_3ds'   => (bool) env('MIDTRANS_3DS', true),
];
