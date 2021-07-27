<?php

namespace App\Http\Controllers;

use App\Expert;
use App\ExpertPrediction;
use Illuminate\Http\Request;
use App\ExpertEmailConfirmation;
use App\ExpertPredictionSummary;
use Illuminate\Support\Facades\Validator;

class ExpertController extends Controller
{
    public function confirmEmail(Request $request){
        $token = $request->token;

        $conf = ExpertEmailConfirmation::where('token', $token)->first();
        if($conf){
            if($conf->is_confirmed){
                return response()->json(['message' => 'confirmed already'], 202);
            }else{
                $conf->update([
                    $conf->is_confirmed = true
                ]);

                 // get expert and update status
                $expert = Expert::findOrFail($conf->expert_id);
                $expert->update([
                    $expert->status = true
                ]);

                return response()->json(['message' => 'Verified'], 200);
            }
        }
    }

    public function postPrediction(Request $request){
        $predictions = $request->predictions;

        $prediction_code = bin2hex(random_bytes(5));
        $expert_id = auth('expert-api')->user()->id;

        $data = ['data' => $request->predictions];
        $validator = Validator::make($data, [
            'data.*.country' => 'min:3|max:20',
            'data.*.league' => 'required|min:3|max:12',
            'data.*.odd' => 'required|numeric|between:1,100',
            'data.*.teamA' => 'required|min:3|max:12',
            'data.*.teamB' => 'required|min:3|max:12',
            'data.*.tip' => 'required|max:10',
        ]);
        if($validator->fails()){
            return response()->json(['message' => 'validation failed'], 500);
        }else{
            foreach($predictions as $prediction){
                $predict = new ExpertPrediction;
                $predict->expert_id = $expert_id;
                $predict->prediction_code = $prediction_code;
                $predict->country = $prediction['country'];
                $predict->league = $prediction['league'];
                $predict->home = $prediction['teamA'];
                $predict->away = $prediction['teamB'];
                $predict->tip = $prediction['tip'];
                $predict->odd = $prediction['odd'];
                $predict->event_date = $prediction['date'];
                $predict->event_time = $prediction['time'];

                $predict->save();
            }
            // create summary
            $summary = new ExpertPredictionSummary;
            $summary->expert_id = $expert_id;
            $summary->forecast_id = $prediction_code;
            $summary->event_count = count($predictions);
            $summary->prog_status = 0;
            $summary->result = 0;
            $summary->save();
        }
        return response()->json($summary, 201);
    }

    public function getAllForecastSummaries(){
        $expert = auth('expert-api')->user()->id;
        $forecasts = ExpertPredictionSummary::where('expert_id', $expert)->get();

        return response()->json($forecasts, 201);
    }
}
