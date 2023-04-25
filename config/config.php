<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    'credentials' => [
        'client_id' => env('FINTECH_MARKET_SDK_API_CLIENT_ID'),
        'client_secret' => env('FINTECH_MARKET_SDK_API_CLIENT_SECRET'),
        'organization' => env('FINTECH_MARKET_SDK_API_CLIENT_COMPANY'),
    ],
    'debug' => env('FINTECH_MARKET_SDK_DEBUG', false),
];
