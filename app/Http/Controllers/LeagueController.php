<?php

namespace App\Http\Controllers;

use App\InternationalCompetition;
use Illuminate\Http\Request;
use App\League;

class LeagueController extends Controller
{
    public function getAll(){
        $leagues = League::all();

        return response()->json($leagues, 200);
    }

    public function getLeaguesByCountry($id){
        $leagues = League::where('country_id', $id)->get();

        return response()->json($leagues, 200);
    }

    public function getAllIntnlCompetitions(){
        $comps = InternationalCompetition::all();

        return response()->json($comps, 200);
    }
}
