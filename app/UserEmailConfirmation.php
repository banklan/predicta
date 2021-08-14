<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserEmailConfirmation extends Model
{
    // protected $guard = [];

    protected $fillable = ['user_id', 'token'];
}
