<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\ExpertEmailConfirmation;
use App\Expert;

class ExpertWelcome extends Mailable
{
    use Queueable, SerializesModels;

    public $expert;
    public $conf;

    public function __construct(Expert $expert, ExpertEmailConfirmation $conf)
    {
        $this->expert = $expert;
        $this->conf = $conf;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.expert_welcome_email')
                    ->from('steve@tipexpats.com');
    }
}
