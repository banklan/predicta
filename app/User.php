<?php

namespace App;


use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $fillable = [
        'first_name', 'last_name', 'username', 'email', 'phone', 'picture',
    ];

    protected $appends = ['fullname', 'user_status', 'created'];

    protected $hidden = [
        'password', 'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getCreatedAttribute($value)
    {
        $date = $this->created_at->format('F jS, Y');
        return $date;
    }

    public function getUserStatusAttribute($value)
    {
        if($this->status == 0){
            return 'Disabled';
        }
        return 'Enabled';
    }

    public function getFullnameAttribute()
    {
        $fullname = $this->name." ".$this->last_name;
        return $this->first_name." ".$this->last_name;
    }

    public function setFirstNameAttribute($value){
        $this->attributes['first_name'] = ucwords($value);
    }

    public function setLastNameAttribute($value){
        $this->attributes['last_name'] = ucwords($value);
    }

    public function subscription(){
        return $this->hasMany('App\Subscription');
    }

    public function payments(){
        return $this->hasMany('App\Payment');
    }

    public function feedbacks(){
        return $this->hasMany('App\feedback');
    }

    public function follows(){
        return $this->hasMany('App\Follow');
    }
}
