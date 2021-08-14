<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Tip extends Model
{
    protected $fillable = ['tip', 'abbrv'];

    protected static function boot(){
        parent::boot();
        static::addGlobalScope('order', function(Builder $builder){
            $builder->orderBy('id', 'asc');
        });
    }

    public function setTipAttribute($value){
        $this->attributes['tip'] = ucwords($value);
    }

    public function setAbbrvAttribute($value){
        $this->attributes['abbrv'] = ucfirst($value);
    }
}
