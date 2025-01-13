<?php

// src/Mail/DynamicEmail.php

namespace Codersgarden\MultiLangMailer\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DynamicEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $subjectText;
    protected $bodyContent;
    protected $fromAddress;
    protected $fromName;
    protected $toAddress;
    protected $attachmentPaths;

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
    public function __construct(string $subjectText, string $bodyContent, string $fromAddress, string $fromName, string $toAddress, array $attachmentPaths = [])
    {
        $this->subjectText = $subjectText;
        $this->bodyContent = $bodyContent;
        $this->fromAddress = $fromAddress;
        $this->fromName = $fromName;
        $this->toAddress = $toAddress;
        $this->attachmentPaths = $attachmentPaths; // Now an array
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $email = $this->subject($this->subjectText)
            ->from($this->fromAddress, $this->fromName)
            ->to($this->toAddress)
            ->html($this->bodyContent);

        // Loop through the attachment paths and attach each file if it exists
        foreach ($this->attachmentPaths as $attachmentPath) {
            if ($attachmentPath && file_exists($attachmentPath)) {
                $email->attach($attachmentPath);
                Log::info('Attachment added: ' . $attachmentPath);
            } else {
                Log::warning('No attachment added. File path may be incorrect or file does not exist: ' . $attachmentPath);
            }
        }

        return $email;
        
        // return $this->subject($this->subjectText)
        //     ->from($this->fromAddress, $this->fromName)
        //     ->to($this->toAddress)
        //     ->html($this->bodyContent);
    }
}
