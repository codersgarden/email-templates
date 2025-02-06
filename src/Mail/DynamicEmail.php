<?php

namespace Codersgarden\MultiLangMailer\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DynamicEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $subjectText;
    public $bodyContent;
    public $attachments;
    public $data;

    /**
     * Create a new message instance.
     *
     * @param string $subjectText
     * @param string $bodyContent
     * @param array $data
     * @param array $attachments
     */
    public function __construct(string $subjectText, string $bodyContent, array $data = [], array $attachments = [])
    {
        $this->subjectText = $subjectText;
        $this->bodyContent = $bodyContent;
        $this->data = $data;
        $this->attachments = $attachments;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        $email = $this->markdown('email-templates::emails.dynamic_email')
            ->subject($this->subjectText)
            ->with([
                'bodyContent' => $this->bodyContent,
                'url' => $this->data['url'] ?? null,
                'buttonText' => $this->data['buttonText'] ?? 'Click Here',
            ]);
        
        return $email;
    }
}
