<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class MailingList extends Model
{
    protected $fillable = ['f_name', 'l_name', 'email'];

    protected $table = 'mailing_list';

    protected $appends = ['fullname', 'joined'];


    protected static function boot(){
        parent::boot();
        static::addGlobalScope('order', function(Builder $builder){
            $builder->orderBy('created_at', 'desc');
        });
    }

    public function setFNameAttribute($value){
        $this->attributes['f_name'] = ucwords($value);
    }

    public function setLNameAttribute($value){
        $this->attributes['l_name'] = ucwords($value);
    }

    public function getFullnameAttribute()
    {
        $fullname = $this->f_name." ".$this->l_name;
        return $fullname;
    }

    public function getJoinedAttribute($value)
    {
        $date = $this->created_at->format('F jS, Y');
        return $date;
    }

    public function getUserStatusAttribute($value)
    {
        if($this->status == true){
            return 'Active';
        }else{
            return 'Inactive';
        }
    }
}
