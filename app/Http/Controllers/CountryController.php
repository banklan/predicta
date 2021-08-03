<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Country;
use App\Bank;

class CountryController extends Controller
{
    public function getAll(){
        $countries = Country::all();

        return response()->json($countries, 200);
    }

    public function getAllBanks(){
        $banks = Bank::all();

        return response()->json($banks, 200);
    }
}
