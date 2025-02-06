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
             $placeholders = [
            'username' => 'XYZ',
            'appname' => 'Example App',
            'email' => 'user@yopmail.com',
            'url' => url('/dashboard'),
            'button_text' => 'Reset Password',
        ];

        $locales = ["en"];


        $attachments = [
            public_path('storage/images/floor.jpg'),
            public_path('storage/images/presse03.pdf'),
        ];

        foreach ($locales as $locale) {
            EmailTemplates::sendEmail("someone@yopmail.com", "demo", $placeholders, $locale, $attachments);
        }

        return response()->json(['message' => 'Email sent successfully']);
    }
}

```
