# Getting Started

## Description
The `EmailTemplates` Package for Laravel provides a dynamic mail template management system. It simplifies the creation, management, and sending of emails with multilingual support, making it easy to update email templates from the database without needing to redeploy the code.

## Installation

You can install the package via Composer:

```bash
composer require codersgarden/multi-lang-mailer:dev-main
```

After installation, add the service provider to your `boostrap/provider.php` file (if you are not using auto-discovery):


```php
'providers' => [
    // Other service providers...
   Codersgarden\MultiLangMailer\Providers\EmailTemplatesServiceProvider::class,
];
```

## Requirements

- **PHP**: >=7.3
- **Laravel**: 8.x, 9.x, 10.x, 11.x
- **Dependencies**:
  - guzzlehttp/guzzle: ^7.0
  - "illuminate/http": "^7.0|^8.0|^9.0|^10.0|^11.0"

### Development Dependencies

- mockery/mockery: ^1.0
- orchestra/testbench: ^6.0
- phpunit/phpunit: ^9.0

---

## Configuration

To customize configuration values, publish the package's configuration file using the following command:

```bash
php artisan vendor:publish --provider="Codersgarden\MultiLangMailer\Providers\EmailTemplatesServiceProvider" --tag="email-templates-config"

```

This will create a `config/email-templates.php` file in your Laravel application where you can set your configuration options:

### `config/email-templates.php`

```
<? php
  

return [
    'default_sender' => env('MAIL_FROM_ADDRESS', 'no-reply@example.com'),
    'supported_locales' => ['en', 'fr', 'de'],
    'placeholder_data_types' => ['string', 'array', 'object', 'int', 'float', 'boolean'],
    'logo' => 'storage/images/floor.jpg',     // Path to the logo image
];

```







