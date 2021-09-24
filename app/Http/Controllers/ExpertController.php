<?php

namespace App\Http\Controllers;

use App\Expert;
use App\ExpertPrediction;
use Illuminate\Http\Request;
use App\ExpertEmailConfirmation;
use App\ExpertPredictionSummary;
use Illuminate\Support\Facades\Validator;
use Image;
use Illuminate\Support\Facades\Hash;
use App\Bank;
use App\BookmakerCode;
use App\Subscription;
use App\Earning;

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
        $cds = $request->cds;

        $prediction_code = bin2hex(random_bytes(5));
        $expert_id = auth('expert-api')->user()->id;

        $data = ['data' => $request->predictions];
        $validator = Validator::make($data, [
            'data.*.country' => 'min:3|max:20',
            'data.*.league' => 'required|min:3|max:20',
            'data.*.odd' => 'required|numeric|between:1,100',
            'data.*.teamA' => 'required|min:3|max:20',
            'data.*.teamB' => 'required|min:3|max:20',
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
                $predict->is_open = true;
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
            $summary->forecast_odd = $request->forecastOdd;
            $summary->total_odds = intval($request->totalOdds * 100);
            // $summary->bet9ja = $request->codes['bet9ja'];
            // $summary->betking = $request->codes['betking'];
            // $summary->merrybet = $request->codes['merrybet'];
            $summary->result = 'O';
            $summary->save();

            $summary->fresh();

            foreach($cds as $cd){
                $bkm = new BookmakerCode;
                $bkm->expert_prediction_summary_id = $summary->id;
                $bkm->expert_id = $expert_id;
                $bkm->bookmaker_id = $cd['bkm']['id'];
                $bkm->bookmaker_code = $cd['code'];
                $bkm->save();
            }

        }
        return response()->json($summary, 201);
    }

    public function getAllForecastSummaries(){
        $expert = auth('expert-api')->user()->id;
        $forecasts = ExpertPredictionSummary::where('expert_id', $expert)->get();

        return response()->json($forecasts, 201);
    }

    public function getPgntdForecastSummaries(){
        $expert = auth('expert-api')->user()->id;

        $forecasts = ExpertPredictionSummary::where('expert_id', $expert)->latest()->paginate(5);
        return response()->json($forecasts, 201);
    }

    public function getExpertSummary($id){
        $summary = ExpertPredictionSummary::with('bookmaker_code')->where('forecast_id', $id)->first();

        return response()->json($summary, 201);
    }

    public function getExpertForecasts($id){
        $forecasts = ExpertPrediction::where('prediction_code', $id)->get();

        return response()->json($forecasts, 201);
    }

    public function UpdateProfile(Request $request){
        $this->validate($request, [
            'profile.first_name' => 'required|min:3|max:30',
            'profile.last_name' => 'required|min:3|max:30',
            'profile.phone' => 'required|max:14'
        ]);

        $expert = auth('expert-api')->user();
        $expert->update([
            $expert->first_name = $request->profile['first_name'],
            $expert->last_name = $request->profile['last_name'],
            $expert->phone = $request->profile['phone'],
        ]);

        return response()->json($expert, 200);
    }

    public function UpdateProfilePic(Request $request){
        $this->validate($request, [
            'image' => 'mimes:jpeg,jpg,bmp,png,gif'
        ]);

        $user = auth('expert-api')->user();
        // check if picture exists for profile, then unlink
        $old_pic = $user->picture;
        if($old_pic){
            $filePath = public_path('/images/profiles/experts/'.$old_pic);
            if(file_exists($filePath)){
                unlink($filePath);
            }
        }

        // save file in folder...later in s3 when ready to deploy
        $file = $request->image;
        if($file){
            $pool = '0123456789abcdefghijklmnopqrstuvwxyz@&';
            $ext = $file->getClientOriginalExtension();
            $filename = substr(str_shuffle($pool), 0, 8).".".$ext;

            //save new file in folder
            $file_loc = public_path('/images/profiles/experts/'.$filename);
            if(in_array($ext, ['jpeg', 'jpg', 'png', 'gif', 'pdf'])){
                $upload = Image::make($file)->resize(220, 280, function($constraint){
                    $constraint->aspectRatio();
                });
                $upload->sharpen(2)->save($file_loc);
            }
        }

        // save path in db
        $user->update([
            $user->picture = $filename
        ]);

        return response()->json($user, 201);
    }

    public function confirmCurrentPassword(Request $request){
        $user = auth('expert-api')->user();
        $current = $user->password;
        $check = Hash::check($request->password, $current);

        return response()->json($check, 200);
    }

    public function updateAccountPassword(Request $request){
        $this->validate($request, [
            'password' => 'required|min:5|max:20|confirmed',
            'password_confirmation' => 'required'
        ]);

        $user = auth('expert-api')->user();
        $new = $request->password;

        $user->update([
            $user->password = Hash::make($new)
        ]);

        return response()->json(['message' => 'Password changed successfully']);
    }

    public function addBankDetails(Request $request){
        $this->validate($request, [
            'details.account_name' => 'required|min:3|max:50',
            'details.bank' => 'required|integer',
            'details.account_type' => 'required|min:3|max:12',
            'details.account_no' => 'required|max:10'
        ]);

        $user = auth('expert-api')->user();
        $user->update([
            $user->bank_id = $request->details['bank'],
            $user->account_type = $request->details['account_type'],
            $user->account_no = $request->details['account_no'],
            $user->account_name = $request->details['account_name'],
        ]);

        return response()->json($user, 200);
    }

    public function getBankDetails(){
        $user = auth('expert-api')->user();
        $bank = Bank::findOrFail($user->bank_id);
        if($user->bank_id){
            return response()->json([
                'bank_id' => $user->bank_id,
                'bank' => $bank,
                'account_type' => $user->account_type,
                'account_no' => $user->account_no,
                'account_name' => $user->account_name
            ]);
        }
    }

    public function updateExpertBankDetails(Request $request){
        $this->validate($request, [
            'details.account_name' => 'required|min:3|max:50',
            'details.bank_id' => 'required',
            'details.account_type' => 'required|min:3|max:12',
            'details.account_no' => 'required|max:10'
        ]);

        $user = auth('expert-api')->user();

        $user->update([
            $user->bank_id = $request->details['bank_id'],
            $user->account_type = $request->details['account_type'],
            $user->account_no = $request->details['account_no'],
            $user->account_name = $request->details['account_name'],
        ]);

        return response()->json($user, 200);
    }

    public function getAtAglance(){
        $user = auth('expert-api')->user()->id;
        $summary = ExpertPredictionSummary::where('expert_id', $user)->get();

        return response()->json($summary, 200);
    }

    public function getExpertSubscriptions(){
        $user = auth('expert-api')->user()->id;
        $subs = Subscription::where('expert_id', $user)->latest()->get();

        return response()->json($subs, 200);
    }

    public function getExpertEarnings(){
        $exp = auth('expert-api')->user()->id;
        $earnings = Earning::where('expert_id', $exp)->get();

        return response()->json($earnings, 200);
    }

    public function getExpertSubscription($id){
        $user = auth('expert-api')->user()->id;
        $sub = Subscription::where('expert_id', $user)->where('sub_id', $id)->first();

        return response()->json($sub, 200);
    }

    public function getUserOtherSubscriptions($id){
        $auth = auth('expert-api')->user()->id;
        $sub = Subscription::where('sub_id', $id)->first();
        $subs = Subscription::where('expert_id', $auth)->where('user_id', $sub->user_id)->where('sub_id', '!=', $id)->get();

        return response()->json($subs, 200);
    }

    public function getExpertForecastPerformance(){
        $auth = auth('expert-api')->user()->id;
        $fcs = ExpertPredictionSummary::where('prog_status', '!=', 0)->where('expert_id', $auth)->latest()->take(5)->get();
        return response()->json($fcs, 200);
    }
}
