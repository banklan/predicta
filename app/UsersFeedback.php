<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\User;

class UsersFeedback extends Model
{
    protected $fillable = ['user_id', 'about', 'target', 'subject', 'body', 'is_read'];

    protected $with = ['user'];

    protected $table = 'users_feedbacks';

    protected $appends = ['receiver'];


    protected static function boot(){
        parent::boot();
        static::addGlobalScope('order', function(Builder $builder){
            $builder->orderBy('created_at', 'desc');
        });
    }

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function setSubjectAttribute($value){
        $this->attributes['subject'] = ucfirst($value);
    }

    public function setBodyAttribute($value){
        $this->attributes['body'] = ucfirst($value);
    }

    public function getReceiverAttribute(){
        if($this->user_id == 9999999){
            $rcv = $this->user_id_to;
            $receiver = User::findOrFail($rcv);
            return $receiver;
        }
    }
}
