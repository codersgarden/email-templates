<?php

return [
    
    // Default sender email address for emails
    'default_sender' => env('MAIL_FROM_ADDRESS', 'no-reply@example.com'),

    // Supported locales for multi-language support
    'supported_locales' => ['en', 'fr', 'de'],

    // Data types supported for placeholders in email templates
    'placeholder_data_types' => ['string', 'array', 'object', 'int', 'float', 'boolean', 'date', 'datetime', 'url'],

    // Uncomment and provide a path if a logo is used in email templates
    // 'logo' => 'storage/images/floor.jpg',     // Path to the logo image

    // URL for redirection (e.g., after an email action)
    // 'url' => 'http://127.0.0.1:8000/dashbaord', // Redirect URL after email action

    // The email address allowed to access certain routes or features
    //'allowedEmail' => 'admin123@yopmail.com',
];
