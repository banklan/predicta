<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\DailyTip;

class DailyTipsSummary extends Model
{
    protected $fillable = ['admin_id', 'tip_code', 'event_count', 'status'];
    protected $with = ['admin', 'daily_tip'];
    protected $table = 'daily_tips_summary';
    protected $appends = ['published', 'success'];

    protected static function boot(){
        parent::boot();
        static::addGlobalScope('order', function(Builder $builder){
            $builder->orderBy('created_at', 'desc');
        });
    }

    public function admin(){
        return $this->belongsTo('App\Admin');
    }

    public function daily_tip(){
        return $this->hasMany('App\DailyTip');
    }

    public function getProgressAttribute(){
        $code = $this->tip_code;
        $predictions = DailyTip::where('tip_code', $code)->get();
        $won = [];
        $lost = [];
        //status = 0 means not played, 1 means lost and 2 means won
        foreach($predictions as $pred){
            if($pred->status === 2){
                $won[] = $pred;
            }else if($pred->status === 1){
                $lost[] = $pred;
            }
        };

        if($predictions->count() === count($won)){
            return '2'; //won
        }else if(count($lost) > 0){
            return '1'; //lost
        }else{
            return '0'; //still running
        }
    }

    public function getPublishedAttribute($value)
    {
        $date = $this->created_at->format('d/m/y');
        return $date;
    }

    public function getSuccessAttribute(){
        $code = $this->tip_code;
        $predictions = DailyTip::where('tip_code', $code)->get();
        $won = [];
        //status = 0 means not played, 1 means lost and 2 means won
        foreach($predictions as $pred){
            if($pred->status === 2){
                $won[] = $pred;
            }
        };

        $game_won = count($won);
        // $total_game = $predictions->count();
        $total_events = $this->event_count;
        if($total_events < 1){
            $success = 0;
        }else if($game_won == 0){
            $success = 0;
        }else{

            $success = ($game_won * 100) / $total_events;
        }
        return round($success);
    }

    // public function getWinningRateAttribute(){
    //     $code = $this->tip_code;
    //     $predictions = DailyTip::where('tip_code', $code)->get();

    //     $won = [];
    //     foreach($predictions as $pred){
    //         if($pred->status === 2){
    //             $won[] = $pred;
    //         }
    //     };
    // }
}
