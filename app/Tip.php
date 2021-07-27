<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Tip extends Model
{
    protected $guards = [];

    // protected static function boot(){
    //     parent::boot();
    //     static::addGlobalScope('order', function(Builder $builder){
    //         $builder->orderBy('id', 'asc');
    //     });
    // }

    public function setTipAttribute($value){
        $this->attributes['tip'] = ucwords($value);
    }
}
