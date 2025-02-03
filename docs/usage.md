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
        $identifier = "register";
        $locales = ["en", "de","fr"];

            $data = [
                'to' => 'user@yopmail.com',
                'from_address' => 'noreply@yopmail.com',
                'username' => 'XYZ',
                'appname' => 'Example App',
                'url' =>route('demo'),
            ];


            $template = DB::table('mail_templates')
                ->where('identifier', $identifier)
                ->first();

            $filePaths = [];
            if ($template && $template->file) {
                $files = explode(',', $template->file);
                foreach ($files as $file) {
                    // Get the path of each file and add to the array
                    $filePaths[] = public_path('storage/images/' . trim($file));
                }
            }

    
            foreach ($locales as $locale) {
                EmailTemplates::sendEmail($identifier, $locale, $data, $filePaths);
            }

              return response()->json(['message' => 'Email sent successfully']);
    }
}

```
