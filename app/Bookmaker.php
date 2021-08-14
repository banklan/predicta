<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Bookmaker extends Model
{
    protected $fillable = ['name', 'logo'];

    protected static function boot(){
        parent::boot();
        static::addGlobalScope('order', function(Builder $builder){
            $builder->orderBy('name', 'asc');
        });
    }

    public function setNameAttribute($value){
        $this->attributes['name'] = ucfirst($value);
    }

}
