<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Earning;

class Expert extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $fillable = [
        'first_name', 'last_name', 'username', 'email', 'phone', 'status', 'picture', 'bank_id', 'account_type', 'account_no', 'account_name'
    ];

    protected $appends = ['fullname', 'expert_status', 'created', 'winning_rate', 'event_count', 'open_events_count', 'total_earning'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email_verified_at',
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

    protected $with = ['bank'];


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

    public function getExpertStatusAttribute($value)
    {
        if($this->status == 0){
            return 'Disabled';
        }
        return 'Enabled';
    }

    public function getCreatedAttribute($value)
    {
        $date = $this->created_at->format('F jS, Y');
        return $date;
    }

    public function prediction_summary(){
        return $this->hasMany('App\ExpertPredictionSummary');
    }

    public function expert_prediction(){
        return $this->hasMany('App\ExpertPrediction');
    }

    public function bank(){
        return $this->belongsTo('App\Bank');
    }

    public function getWinningRateAttribute(){
        $period = Carbon::today()->subDays(90);
        $forecasts = ExpertPredictionSummary::where('expert_id', $this->id)->where('created_at', '>=', $period)->where('prog_status','!=', 0)->get();
        $win = [];
        foreach($forecasts as $fc){
            if($fc->prog_status == 2){
                $win[] = $fc;
            }
        }
        if(count($win) > 0){
            $win_rate = round((count($win) * 100 )/ $forecasts->count(), 2);
        }else{
            $win_rate = 0;
        }
        return $win_rate;
    }

    public function getEventCountAttribute(){
        $events = ExpertPredictionSummary::where('expert_id', $this->id)->count();
        return $events;
    }

    public function getOpenEventsCountAttribute(){
        $events = ExpertPredictionSummary::where('expert_id', $this->id)->where('prog_status', 0)->count();

        return $events;
    }

    public function getOpenEventsAttribute(){
        $events = ExpertPredictionSummary::where('expert_id', $this->id)->get();

        foreach($events as $event){
            $preds = ExpertPrediction::where('prediction_code', $event->forecast->id)->get();
            $started = [];
            foreach($preds as $pred){
                if($pred->is_open == false){
                    $started[] =  $pred;
                }
            }
            if(count($started) > 0){

            }
        }
        $events = ExpertPrediction::where('prediction_code', $event->forecast->id)->get();
        return $events;
    }

    public function subscription(){
        return $this->hasMany('App\Subscription');
    }

    public function earnings(){
        return $this->hasMany('App\Earning');
    }

    public function getTotalEarningAttribute(){
        $earnings = Earning::where('expert_id', $this->id)->where('is_settled', true)->get();

        $settled = 0;
        foreach ($earnings as $earn) {
            $settled = $earn->exp_amount + $settled;
        }
        return $settled;
    }
}
