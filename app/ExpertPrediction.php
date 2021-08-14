<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ExpertPrediction extends Model
{
    // protected $guarded = [];

    protected $fillable = ['expert_id', 'prediction_code', 'country', 'league', 'home', 'away', 'tip', 'odd', 'status'];

    protected $table = 'predictions';

    protected $appends = ['date', 'time'];

    protected $with = ['expert'];


    protected static function boot(){
        parent::boot();
        static::addGlobalScope('order', function(Builder $builder){
            $builder->orderBy('prediction_code', 'desc');
        });
    }

    public function setCountryAttribute($value){
        $this->attributes['country'] = ucwords($value);
    }

    public function setLeagueAttribute($value){
        $this->attributes['league'] = ucwords($value);
    }

    public function setHomeAttribute($value){
        $this->attributes['home'] = ucwords($value);
    }

    public function setAwayAttribute($value){
        $this->attributes['away'] = ucwords($value);
    }

    public function getDateAttribute($value)
    {
        $date = $this->created_at->format('d/m/y');
        return $date;
    }

    public function getTimeAttribute($value)
    {
        $time = $this->created_at->format('h:i');
        return $time;
    }

    public function expert(){
        return $this->belongsTo('App\Expert');
    }
}
