<?php

namespace App\Mail;

use App\UsersFeedback;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class FeedbackPostedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $fb;

    public function __construct(User $user, UsersFeedback $fb)
    {
        $this->user = $user;
        $this->fb = $fb;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('admin@surepredict.com')->view('emails.feedback_posted');
    }
}
