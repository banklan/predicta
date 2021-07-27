<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Team;
use App\NationalTeam;

class TeamController extends Controller
{
    public function getTeamsByLeague($id){
        $teams = Team::where('league_id', $id)->get();

        return response()->json($teams, 200);
    }

    public function getAllNatnlTeams(){
        $teams = NationalTeam::all();

        return response()->json($teams, 200);
    }
}
