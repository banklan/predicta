<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\ExpertPrediction;

class ExpertPredictionSummary extends Model
{
    protected $fillable = ['expert_id', 'forecast_id', 'event_count', 'prog_status', 'forecast_odd'. 'total_odds', 'bet9ja', 'betking', 'merrybet'];

    protected $table = 'forecast_summary';

    protected $with = ['expert'];

    protected $appends = ['status', 'progress', 'is_opened', 'published', 'predictions'];

    protected static function boot(){
        parent::boot();
        static::addGlobalScope('order', function(Builder $builder){
            $builder->orderBy('created_at', 'desc');
        });
    }

    public function expert(){
        return $this->belongsTo('App\Expert');
    }

    public function getStatusAttribute($value){
        $status = $this->prog_status;
        if($status == 0){
            return 'Open';
        }else{
            return 'Concluded';
        }
    }

    public function getProgressAttribute(){
        $code = $this->forecast_id;
        $predictions = ExpertPrediction::where('prediction_code', $code)->get();
        $won = array();
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
            return '1'; //won
        }else if(count($lost) > 0){
            return '0'; //lost
        }else if(count($won) == 0 && count($lost) == 0){
            return '2'; //still running
        }
    }

    public function getIsOpenedAttribute(){
        $code = $this->forecast_id;
        $predictions = ExpertPrediction::where('prediction_code', $code)->get();
        $started = [];
        foreach($predictions as $item){
            if($item->is_open == false){
                $started[] = $item;
            }
        }
        if(count($started) > 0){
            return false;
        }else{
            return true;
        }
    }

    public function getPublishedAttribute($value)
    {
        $date = $this->created_at->format('d/m/y');
        return $date;
    }

    public function getPredictionsAttribute(){
        $pc = $this->forecast_id;
        $predictions = ExpertPrediction::where('prediction_code', $pc)->get();
        return $predictions;
    }

    public function getOpenedForecastAttribute(){
        // $pc = $this->forecast_id;
        // $events = ExpertPrediction::where('prediction_code', $pc)->get();
        // // $summary = ExpertPredictionSummary::where('forecast_id', $pc)
        // $opened = [];
        // foreach($events as $ev){
        //     if($ev->is_open == 1){
        //         $opened[] = $ev;
        //     }
        // }
        // if(count($opened) === )
    }
}
