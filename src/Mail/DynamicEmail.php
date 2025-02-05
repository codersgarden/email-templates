<?php

// src/Mail/DynamicEmail.php

namespace Codersgarden\MultiLangMailer\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DynamicEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $subjectText;
    protected $bodyContent;
    protected $attachmentPaths;
    protected $placeholders;

    /**
     * Create a new message instance.
     *
     * @param string $subjectText
     * @param string $bodyContent
     * @param string $fromAddress
     * @param string $fromName
     * @param string $toAddress
     * @return void
     */
    public function __construct(string $subjectText, string $bodyContent, array $placeholders = [], array $attachments = [])
    {
        $this->subjectText = $subjectText;
        $this->bodyContent = $bodyContent;
        $this->placeholders = $placeholders;
        $this->attachments = $attachments;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->markdown('email-templates::emails.dynamic_email')->subject($this->subjectText)->with([
            'bodyContent' => $this->bodyContent,
            'url' => $this->placeholders['url'] ?? null,
            'buttonText' => $this->placeholders['buttonText'] ?? 'Click Here',
        ]);

        // Attach files if any
        foreach ($this->attachments as $attachment) {
            if (Storage::exists($attachment)) {
                $email->attach($attachment);
            }
        }

        return $email;
    }
}
