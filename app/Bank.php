<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Bank extends Model
{
    protected $fillable = ['name'];

    protected static function boot(){
        parent::boot();
        static::addGlobalScope('order', function(Builder $builder){
            $builder->orderBy('name', 'asc');
        });
    }

    public function expert(){
        return $this->hasOne('App\Expert');
    }

    public function setNameAttribute($value){
        $this->attributes['name'] = ucwords($value);
    }
}
