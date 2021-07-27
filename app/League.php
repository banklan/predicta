<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class League extends Model
{
    protected $guards = [];

    protected $with = ['team'];


    protected static function boot(){
        parent::boot();
        static::addGlobalScope('order', function(Builder $builder){
            $builder->orderBy('country_id', 'desc');
        });
    }


    public function setLeagueAttribute($value){
        $this->attributes['league'] = ucwords($value);
    }

    public function setAbbrvAttribute($value){
        $this->attributes['abbrv'] = ucwords($value);
    }

    public function team(){
        return $this->hasMany('App\Team');
    }

    public function country(){
        return $this->belongsTo('App\Country');
    }
}
