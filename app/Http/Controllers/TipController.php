<?php

namespace App\Http\Controllers;

use App\OddType;
use Illuminate\Http\Request;
use App\Tip;
use App\Bookmaker;
use App\DailyTip;
use App\DailyTipsSummary;
use App\Expert;
use App\ExpertPredictionSummary;


class TipController extends Controller
{
    public function getAllTips(){
        $tips = Tip::all();

        return response()->json($tips, 200);
    }

    public function getOddTypes(){
        $odds = OddType::all();

        return response()->json($odds, 200);
    }

    public function getAllBookmakers(){
        $bms = Bookmaker::all();

        return response()->json($bms, 200);
    }

    public function getFeaturedDailyTips(){
        $tip = DailyTipsSummary::where('is_featured', true)->latest()->first();
        $tips = DailyTip::where('daily_tips_summary_id', $tip->id)->where('is_featured', true)->get();
        $tipDate = $tip->tip_date;

        return response()->json(['tips' => $tips, 'tipDate' => $tipDate], 200);
    }

    public function getBriefWonDailyTips(){
        $won_tips = DailyTip::where('status', 2)->where('is_featured', true)->orderBy('event_date', 'desc')->take(6)->get();

        return response()->json($won_tips, 200);
    }

    public function getTopExpertsBrief(){
        $sort = Expert::all()->sortByDesc('winning_rate')->values()->take(5);

        return response()->json($sort, 200);
    }

    public function getAllExperts(){
        $experts = Expert::all();

        return response()->json($experts, 200);
    }

    public function getTipExpert($id){
        $expert = Expert::where('expert_id', $id)->with('prediction_summary')->first();

        return response()->json($expert, 200);
    }

    public function getExpertWonEvents($id){
        $expert = Expert::where('expert_id', $id)->first();
        $events = ExpertPredictionSummary::where('expert_id', $expert->id)->where('prog_status', 2)->get();

        return response()->json($events, 200);
    }

    public function getExpertsByWinningRate(){
        $exps = Expert::all()->sortByDesc('winning_rate')->values();

        return response()->json($exps, 200);
    }

    public function getTodaysTips(){
        $tip = DailyTipsSummary::where('is_featured', true)->latest()->first();
        $tips = DailyTip::where('daily_tips_summary_id', $tip->id)->get();

        return response()->json(['tips' => $tips, 'tipDate'=>$tip->tip_date], 200);
    }

    public function getWonTips(){
        $tips = DailyTip::where('status', 2)->take('30')->get();

        return response()->json($tips, 200);
    }
}
