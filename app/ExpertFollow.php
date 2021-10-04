<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpertFollow extends Model
{
    protected $table = 'expert_follow';
    protected $fillable = ['expert_id','user_id'];
    protected $with = ['expert', 'user'];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function expert(){
        return $this->belongsTo('App\Expert');
    }
}
