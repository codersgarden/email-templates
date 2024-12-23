<?php

return [
    'default_sender' => env('MAIL_FROM_ADDRESS', 'no-reply@example.com'),
    'supported_locales' => ['en', 'fr', 'de'],
    'placeholder_data_types' => ['string', 'array', 'object', 'int', 'float', 'boolean'],
];
