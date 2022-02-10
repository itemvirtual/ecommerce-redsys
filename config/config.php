<?php


return [

    /*
    |--------------------------------------------------------------------------
    | Redsys
    |--------------------------------------------------------------------------
    |
    | Nothing yet
    |
    */

    'environment' => env('ECOMMERCE_REDSYS_ENVIRONMENT', 'test'),

    'parameters' => [
        'test' => [
            'key' => env('ECOMMERCE_REDSYS_KEY', 'sq7HjrUOBfKmC576ILgskD5srU870gJ7'),
            'merchant_code' => env('ECOMMERCE_REDSYS_CODE', '999008881'),
            'terminal' => env('ECOMMERCE_REDSYS_TERMINAL', 1),
            'currency' => env('ECOMMERCE_REDSYS_CURRENCY', 978),
            'notification_url' => env('ECOMMERCE_REDSYS_NOTIFICATION_URL', null),
            'url_ok' => env('ECOMMERCE_REDSYS_URL_OK', false),
            'url_ko' => env('ECOMMERCE_REDSYS_URL_KO', false),
            'trade_name' => env('ECOMMERCE_REDSYS_TRADE_NAME', 'Ecommerce Redsys Shop'),
            'titular' => env('ECOMMERCE_REDSYS_TITULAR', 'Your Name'),
            'language' => env('ECOMMERCE_REDSYS_LANGUAGE', '001'),
        ],

        'live' => [
            'key' => env('ECOMMERCE_REDSYS_KEY', null),
            'merchant_code' => env('ECOMMERCE_REDSYS_CODE', null),
            'terminal' => env('ECOMMERCE_REDSYS_TERMINAL', 1),
            'currency' => env('ECOMMERCE_REDSYS_CURRENCY', 978),
            'notification_url' => env('ECOMMERCE_REDSYS_NOTIFICATION_URL', null),
            'url_ok' => env('ECOMMERCE_REDSYS_URL_OK', null),
            'url_ko' => env('ECOMMERCE_REDSYS_URL_KO', null),
            'trade_name' => env('ECOMMERCE_REDSYS_TRADE_NAME', null),
            'titular' => env('ECOMMERCE_REDSYS_TITULAR', null),
            'language' => env('ECOMMERCE_REDSYS_LANGUAGE', '001'),
        ]
    ],

];