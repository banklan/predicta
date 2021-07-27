<?php

namespace App;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'phone', 'status', 'role', 'password'
    ];

    protected $appends = ['fullname', 'admin_status', 'admin_role', 'created'];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email_verified_at'
    ];



    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $date = [
        'updated_at'
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

    public function setFirstNameAttribute($value){
        $this->attributes['first_name'] = ucwords($value);
    }

    public function setLastNameAttribute($value){
        $this->attributes['last_name'] = ucwords($value);
    }

    public function getFullnameAttribute()
    {
        $fullname = $this->name." ".$this->last_name;
        return $this->first_name." ".$this->last_name;
    }

    public function getAdminStatusAttribute($value)
    {
        if($this->status == 0){
            return 'Disabled';
        }
        return 'Enabled';
    }

    public function getAdminRoleAttribute($value)
    {
        if($this->role == 'admin'){
            return 'Admin';
        }
        return 'Super-User';
    }

    public function getCreatedAttribute($value)
    {
        $date = $this->created_at->format('F jS, Y');
        return $date;
    }
}
