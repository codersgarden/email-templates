<?php

namespace Codersgarden\MultiLangMailer\Services;

use Codersgarden\MultiLangMailer\Mail\DynamicEmail;
use Codersgarden\MultiLangMailer\Models\MailTemplate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailTemplateService
{
    protected $placeholderService;

    public function __construct(PlaceholderService $placeholderService)
    {
        $this->placeholderService = $placeholderService;
    }

    /**
     * Send an email using a template identifier.
     *
     * @param string $identifier
     * @param array $data
     * @param string|null $locale
     * @return void
     */



     public function sendEmail(string $email, string $identifier, array $placeholders, string $locale, array $attachments = [])
     {
         $template = MailTemplate::where('identifier', $identifier)->first();
     
         if (!$template) {
             throw new \Exception("Email template '{$identifier}' not found.");
         }
     
         if ($template->has_attachment == 1 && empty($attachments)) {
             throw new \Exception("Attachments are required for this email template.");
         }
     
         $translatedMail = $template->translation($locale);
         if (!$translatedMail) {
             throw new \Exception("Email template '{$identifier}' does not have a translation for locale '{$locale}'");
         }
     
         $url = $placeholders['url'] ?? null;
         $buttonText = $placeholders['button_text'] ?? 'Click Here';
     
         // Ensure placeholders are converted to strings
         $placeholders = array_map(function ($value) {
             return is_array($value) ? json_encode($value) : $value;
         }, $placeholders);
     
         $body = $this->placeholderService->replacePlaceholders($translatedMail->body, $placeholders);
     
         $emailObj = new DynamicEmail(
             $this->placeholderService->replacePlaceholders($translatedMail->subject, $placeholders),
             $body,
             compact('url', 'buttonText')
         );
     
         // Handle attachments properly
         foreach ($attachments as $attachment) {
             if (file_exists($attachment)) {
                 $emailObj->attach($attachment);
             } else {
                 Log::error("Attachment not found: {$attachment}");
             }
         }
     
         // Send the email with attachments
         Mail::to($email)->send($emailObj);
     }
}
