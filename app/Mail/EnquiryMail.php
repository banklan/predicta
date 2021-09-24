<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Enquiry;

class EnquiryMail extends Mailable
{
    use Queueable, SerializesModels;

    public $enquiry;

    public function __construct(Enquiry $enquiry)
    {
        $this->enquiry = $enquiry;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->enquiry->email)->view('emails.enquiry_email');
    }
}
