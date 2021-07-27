<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class NationalTeam extends Model
{
    protected $guards = [];


    protected static function boot(){
        parent::boot();
        static::addGlobalScope('order', function(Builder $builder){
            $builder->orderBy('country', 'asc');
        });
    }

    public function setCountryAttribute($value){
        $this->attributes['country'] = ucwords($value);
    }

    public function setAbbrvAttribute($value){
        $this->attributes['abbrv'] = ucwords($value);
    }
}
