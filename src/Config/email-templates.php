<?php

return [
    
    'default_sender' => env('MAIL_FROM_ADDRESS', 'no-reply@example.com'),
    'supported_locales' => ['en', 'fr', 'de'],
    'placeholder_data_types' => ['string', 'array', 'object', 'int', 'float', 'boolean','date','datetime','url'],
    // 'logo' => 'storage/images/floor.jpg',     // Path to the logo image
    //'url'=>'http://127.0.0.1:8000/dashbaord', // Redirect url
];
