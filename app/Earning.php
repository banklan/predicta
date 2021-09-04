<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Earning extends Model
{
    protected $fillable = ['subsription_id', 'total', 'exp_amount', 'admin_amount', 'is_settled'];

    protected static function boot(){
        parent::boot();
        static::addGlobalScope('order', function(Builder $builder){
            $builder->orderBy('created_at', 'desc');
        });
    }

    public function subscription(){
        return $this->belongsTo('App\Subscription');
    }

    public function expert(){
        return $this->belongsTo('App\Expert');
    }

    // public function getEarningStatusAttribute(){
    //     if($this->status == 0){
    //         return 'Not Yet Settled';
    //     }
    // }
}
