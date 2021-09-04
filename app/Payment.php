<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['user_id', 'subscription_id', 'amount', 'tx_id', 'status'];

    protected $with = ['user', 'subscription'];


    public function user(){
        return $this->belongsTo('App\User');
    }

    public function subscription(){
        return $this->belongsTo('App\Subscription');
    }
}
