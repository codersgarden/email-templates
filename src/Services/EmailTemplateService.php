<?php

namespace Codersgarden\MultiLangMailer\Services;

use Codersgarden\MultiLangMailer\Mail\DynamicEmail;
use Codersgarden\MultiLangMailer\Modal\Template;
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
    public function sendEmail(string $identifier, array $data = [], string $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        $template = Template::where('identifier', $identifier)->firstOrFail();
        $translation = $template->translation($locale);

        if (!$translation) {
            throw new \Exception("Email template '{$identifier}' does not have a translation for locale '{$locale}'.");
        }

        // Replace placeholders
        $subject = $this->placeholderService->replacePlaceholders($translation->subject, $data);
        $body = $this->placeholderService->replacePlaceholders($translation->body, $data);

        // Send email using a generic DynamicEmail Mailable
        Mail::send(new DynamicEmail($subject, $body, $data['from_address'] ?? config('mail.from.address'), $data['from_name'] ?? config('mail.from.name'), $data['to']));
    }
}
