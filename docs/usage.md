# Usage

## Importing the Class

To use the `EmailTemplates` package, first import the main class:

```php
use Codersgarden\MultiLangMailer\Facades\EmailTemplates;
```

## Example Usage in a Controller

Here’s a basic example of using the package in a Laravel controller to send an email:

```php
class TestController extends Controller
{


        public function sendMail()
    {
        // Email template identifier (use the template name)
        $identifier = "welcome_mail";

        // Supported locales for the email (e.g., English, French, Spanish)
        $locales = ["en"]; // Available languages: en, fr, sp, de, es

        // Data to be passed to the email template
        $data = [
            'to' => 'user@yopmail.com',
            'from_address' => 'noreply@yopmail.com',
            'username' => 'XYZ',
            'appname' => 'Example App',  // Add other parameters as needed
        ];

        // Send the email using the template identifier, selected locales, and data
        EmailTemplates::sendEmail($identifier, $locales, $data);

        return response()->json(['message' => 'Email sent successfully']);
    }
}

```
