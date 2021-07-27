<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ExpertPrediction extends Model
{
    protected $guards = [];

    protected $table = 'predictions';

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
}
