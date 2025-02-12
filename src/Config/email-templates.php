<?php

return [

    'default_sender' => env('MAIL_FROM_ADDRESS', 'no-reply@example.com'),
    'supported_locales' => ['en', 'fr', 'de'],
    'placeholder_data_types' => ['string', 'array', 'object', 'int', 'float', 'boolean', 'date', 'datetime', 'url'],
    // 'logo' => 'storage/images/floor.jpg',     // Path to the logo image

    // URL for redirection (e.g., after an email action)
    // 'fallbackUrl' => 'index', // Redirect URL after email action

    // The email address allowed to access certain routes or features
    //'allowedEmail' => ['admin123@yopmail.com'],
];
