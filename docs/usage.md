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
    /**
     * Send an email using a predefined template with dynamic content and attachments.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMail()
    {
        // Define dynamic placeholders that will be inserted into the email template
        $placeholders = [
            'username' => 'XYZ',            // Placeholder for the username
            'appname' => 'Example App',     // Placeholder for the app name
            'email' => 'user@yopmail.com',  // Placeholder for the user's email address
            'url' => url('/dashboard'),     // Placeholder for the URL (dashboard)
            'button_text' => 'Reset Password', // Placeholder for the button text (used in the email template)
        ];

        // Define the locales/languages to send the email in (English here)
        $locales = ["en"];

        // Define an array of file paths for attachments to be included in the email
        $attachments = [
            public_path('storage/images/floor.jpg'),     // Image file to attach
            public_path('storage/images/presse03.pdf'),  // PDF file to attach
        ];

        // Loop through each locale and send the email with the corresponding placeholders and attachments
        foreach ($locales as $locale) {
            EmailTemplates::sendEmail(
                "someone@yopmail.com", // Recipient email address
                "demo",                // The template identifier for the email (can be mapped to a template in the database)
                $placeholders,         // Array of dynamic values to be inserted into the email template
                $locale,               // Locale (language) of the email, here it is English ("en")
                $attachments           // Array of files to attach to the email
            );
        }

        // Return a JSON response indicating the email was sent successfully
        return response()->json(['message' => 'Email sent successfully']);
    }
}

```
