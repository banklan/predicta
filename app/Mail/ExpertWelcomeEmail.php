<?php

namespace App\Mail;

use App\ExpertEmailConfirmation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Expert;

class ExpertWelcomeEmail extends Mailable
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
        return $this->from('admin@tipexpats.com')->view('emails.expert_welcome_mail');
    }
}
