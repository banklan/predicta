<?php

namespace App\Http\Controllers;

use App\OddType;
use Illuminate\Http\Request;
use App\Tip;

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
}
