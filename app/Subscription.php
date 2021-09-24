<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ExpertPredictionSummary;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Subscription extends Model
{
    protected $fillable = ['user_id', 'expert_id', 'odd_cat', 'amount', 'expiry', 'status'];

    protected $with = ['user', 'expert', 'earning'];

    protected $date = ['created_at', 'expiry'];

    protected $appends = ['events', 'expiry_status', 'active_status'];

    protected static function boot(){
        parent::boot();
        static::addGlobalScope('order', function(Builder $builder){
            $builder->orderBy('created_at', 'desc');
        });
    }


    public function user(){
        return $this->belongsTo('App\User');
    }

    public function expert(){
        return $this->belongsTo('App\Expert');
    }

    public function getEventsAttribute(){
        $expert = $this->expert_id;
        $odd_cat = $this->odd_cat;
        $events = ExpertPredictionSummary::where('expert_id', $expert)
                                            ->where('forecast_odd', $odd_cat)
                                            ->where('is_available', true)
                                            ->get();
        return $events;
    }

    public function getExpiryStatusAttribute(){
        $exp = $this->expiry;
        $now = Carbon::now();
        if($exp < $now){
            return 'Expired';
        }else{
            return 'Active';
        }
    }

    public function earning(){
        return $this->hasOne('App\Earning');
    }

    public function payment(){
        return $this->hasOne('App\Payment');
    }

    public function getActiveStatusAttribute(){
        $exp = $this->expiry;
        $now = Carbon::now();
        if($exp < $now){
            return 'Expired';
        }else{
            return 'Active';
        }
    }
}
