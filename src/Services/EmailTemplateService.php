<?php

namespace Codersgarden\MultiLangMailer\Services;

use Codersgarden\MultiLangMailer\Mail\DynamicEmail;
use Codersgarden\MultiLangMailer\Models\MailTemplate;
use Codersgarden\MultiLangMailer\Models\Template;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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
            throw new \Exception("attachments are required for this email template");
        }

        $translatedMail = $template->translation($locale);
        if (!$translatedMail) {
            throw new \Exception("Email template '{$identifier}' does not have a translation for locale '{$locale}'");
        }

        $url = $placeholders['urls_and_buttons'][0]['url'] ?? null;
        $buttonText = $placeholders['urls_and_buttons'][0]['button_text'] ?? 'Click Here';
        $body = $this->placeholderService->replacePlaceholders($translatedMail->body, $placeholders);

        Mail::to($email)->send(new DynamicEmail(
            $this->placeholderService->replacePlaceholders($translatedMail->subject, $placeholders),
            $body,
            compact('url', 'buttonText'),
            $attachments
        ));
    }
}
