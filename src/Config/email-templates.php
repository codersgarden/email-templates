<?php

return [
    'default_sender' => env('MAIL_FROM_ADDRESS', 'no-reply@example.com'),
    'supported_locales' => ['en', 'fr', 'de'],
    'placeholder_data_types' => ['string', 'array', 'object', 'int', 'float', 'boolean'],


    'disks' => [
        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
        ],
    ],

    'markdown' => [
    'theme' => 'default',
    'paths' => [resource_path('views/vendor/mail')],
],


];
