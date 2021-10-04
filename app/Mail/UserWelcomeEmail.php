<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use App\UserEmailConfirmation;

class UserWelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $user;
    public $conf;

    public function __construct(User $user, UserEmailConfirmation $conf)
    {
        $this->user = $user;
        $this->conf = $conf;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('admin@tipexpats.com')->view('emails.user_welcome_mail');
    }
}
