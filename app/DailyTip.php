<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class DailyTip extends Model
{
    protected $fillable = ['admin_id', 'country', 'league', 'tip_code', 'home', 'away', 'tip', 'odd', 'event_date', 'event_time', 'status', 'is_featured'];

    protected $with = ['admin'];

    protected $appends = ['date', 'time', 'game'];


    protected static function boot(){
        parent::boot();
        static::addGlobalScope('order', function(Builder $builder){
            $builder->orderBy('id', 'asc');
        });
    }

    public function setCountryAttribute($value){
        $this->attributes['country'] = ucwords($value);
    }

    public function setLeagueAttribute($value){
        $this->attributes['league'] = ucwords($value);
    }

    public function setHomeAttribute($value){
        $this->attributes['home'] = ucwords($value);
    }

    public function setAwayAttribute($value){
        $this->attributes['away'] = ucwords($value);
    }

    public function admin(){
        return $this->belongsTo('App\Admin');
    }

    public function daily_tip_summary(){
        return $this->belongsTo('App\DailyTipSummary');
    }

    public function getDateAttribute($value)
    {
        // $date = Carbon::parse($this->event_date)->toFormattedDateString();
        $date = Carbon::parse($this->event_date)->format('d/m/y');
        return $date;
    }

    public function getTimeAttribute($value)
    {
        $time = Carbon::parse($this->event_time)->format('h:ia');
        return $time;
    }

    public function getGameAttribute(){
        $home = $this->home;
        $away = $this->away;
        $game = $home.' vs '.$away;
        return $game;
    }
}
