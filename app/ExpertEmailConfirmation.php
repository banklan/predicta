<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpertEmailConfirmation extends Model
{
    // protected $guard = [];

    protected $fillable = ['expert_id', 'token'];
}
