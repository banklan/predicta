<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\MailingList;
use App\DailyTip;
use App\DailyTipsSummary;

class DailyTipsFromSurepredict extends Mailable
{
    use Queueable, SerializesModels;

    public $tips;
    public $user;
    public $tip_summary;

    public function __construct(\Illuminate\Database\Eloquent\Collection $tips, MailingList $user, DailyTipsSummary $tip_summary)
    {
        $this->tips = $tips;
        $this->user = $user;
        $this->tip_summary = $tip_summary;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.daily_tips_from_surepredict')
                    ->with(['tips'=> $this->tips]);
    }
}
