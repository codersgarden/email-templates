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
            $identifier = "Reset Password";
            $locales = ["en"];
        
           $data = [
            'to' => 'user@yopmail.com',
            'from_address' => 'noreply@yopmail.com',
            'from_name' => 'Example App',
            'username' => 'XYZ',
            'appname' => 'Example App',
            'url' => url('/demo'),
            'logo' => asset('storage/images/floor.jpg'),
            'button_text' => 'Confirm Your Email',
        ];
        
            $template = DB::table('mail_templates')->where('identifier', $identifier)->first();
        
            $filePaths = [];
        
            if ($template && $template->file) {
                $files = explode(',', $template->file);
                foreach ($files as $file) {
                    $filePath = public_path('storage/images/' . trim($file));
                    if (file_exists($filePath)) {
                        $filePaths[] = $filePath;
                    } else {
                        Log::warning("Attachment file not found: " . $filePath);
                    }
                }
            }
        
            Log::info('Attachments prepared: ', $filePaths);
        
            foreach ($locales as $locale) {
                EmailTemplates::sendEmail($identifier, $locale, $data, $filePaths);
            }


            return response()->json(['message' => 'Email sent successfully']);
    }
}

```
