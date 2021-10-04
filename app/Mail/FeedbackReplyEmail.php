<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use App\UsersFeedback;

class FeedbackReplyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $receiver;
    public $fb;

    public function __construct(User $receiver, UsersFeedback $fb)
    {
        $this->receiver = $receiver;
        $this->fb = $fb;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('admin@tipexpats.com')->view('emails.feedback_replied');
    }
}
