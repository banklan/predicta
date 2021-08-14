<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = ['user_id', 'expert_id', 'odd_cat', 'amount', 'expiry', 'status'];

    protected $with = ['user', 'expert'];

    protected $date = ['created_at', 'expiry'];


    public function user(){
        return $this->belongsTo('App\User');
    }

    public function expert(){
        return $this->belongsTo('App\Expert');
    }
}
