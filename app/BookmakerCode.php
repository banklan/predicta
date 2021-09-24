<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class BookmakerCode extends Model
{
    protected $fillable = ['forecast_summary_id', 'expert_id', 'bookmaker_id', 'bookmaker_code'];
    protected $table = 'bookmakers_codes';

    protected $with = ['expert', 'forecast_summary', 'bookmaker'];

    protected static function boot(){
        parent::boot();
        static::addGlobalScope('order', function(Builder $builder){
            $builder->orderBy('id', 'asc');
        });
    }

    public function expert(){
        return $this->belongsTo('App\Expert');
    }

    public function forecast_summary(){
        return $this->belongsTo('App\ExpertPredictionSummary');
    }

    public function bookmaker(){
        return $this->belongsTo('App\Bookmaker');
    }
}
