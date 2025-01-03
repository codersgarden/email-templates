<?php

// src/Mail/DynamicEmail.php

namespace Codersgarden\MultiLangMailer\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DynamicEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $subjectText;
    protected $bodyContent;
    protected $fromAddress;
    protected $fromName;
    protected $toAddress;

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
    public function __construct(string $subjectText, string $bodyContent, string $fromAddress, string $fromName, string $toAddress)
    {
        $this->subjectText = $subjectText;
        $this->bodyContent = $bodyContent;
        $this->fromAddress = $fromAddress;
        $this->fromName = $fromName;
        $this->toAddress = $toAddress;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subjectText)
            ->from($this->fromAddress, $this->fromName)
            ->to($this->toAddress)
            ->html($this->bodyContent);
    }
}
