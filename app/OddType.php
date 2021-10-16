<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OddType extends Model
{
    protected $fillable = ['odd'];

    protected $table = 'odd_types';
}
