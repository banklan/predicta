<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ExpertPredictionSummary extends Model
{
    protected $guards = [];

    protected $table = 'forecast_summary';

    protected $with = ['expert'];

    protected $appends = ['status'];

    protected static function boot(){
        parent::boot();
        static::addGlobalScope('order', function(Builder $builder){
            $builder->orderBy('created_at', 'desc');
        });
    }

    public function expert(){
        return $this->belongsTo('App\Expert');
    }

    public function getStatusAttribute($value){
        $status = $this->prog_status;
        if($status == 0){
            return 'Open';
        }else{
            return 'Concluded';
        }
    }

    // public function getEvResultAttribute($value){
    //     $rez = $this->result;
    //     if($rez == 'O'){
    //         return 'Open';
    //     }elseif($rez == 'L'){
    //         return 'Lost';
    //     }else{
    //         return 'Won';
    //     }
    // }
}
