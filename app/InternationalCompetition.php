<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InternationalCompetition extends Model
{
    protected $guards = [];

    public function setCompetitionAttribute($value){
        $this->attributes['competition'] = ucwords($value);
    }
}
