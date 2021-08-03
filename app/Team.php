<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Team extends Model
{
    protected $guards = [];

    protected $with = ['league', 'country'];


    protected static function boot(){
        parent::boot();
        static::addGlobalScope('order', function(Builder $builder){
            $builder->orderBy('team', 'asc');
        });
    }

    public function setTeamAttribute($value){
        $this->attributes['team'] = ucwords($value);
    }

    public function setAbbrvAttribute($value){
        $this->attributes['abbrv'] = ucwords($value);
    }

    public function league(){
        return $this->belongsTo('App\League');
    }

    public function country(){
        return $this->belongsTo('App\Country');
    }
}
