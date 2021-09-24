<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DailyTipsMailing extends Model
{
    protected $fillable = ['daily_tips_summary_id'];

    public function daily_tips_summary(){
        return $this->belongsTo('App\DailyTipsSummary');
    }
}
