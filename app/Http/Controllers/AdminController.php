<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Admin;
use App\Bank;
use App\Expert;
use App\ExpertPrediction;
use App\ExpertPredictionSummary;
use App\Country;
use App\League;
use Image;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin-api');
    }

    public function getAllSuperUsers(){
        $admins = Admin::latest()->get();

        return response()->json($admins, 200);
    }

    public function getSuperUser($id){
        $admin = Admin::findOrFail($id);

        return response()->json($admin, 200);
    }

    public function updateSuperUser(Request $request, $id){
        $user = Admin::findOrFail($id);

        $validator = $this->validate($request, [
            'user.first_name' => 'required|min:3|max:30',
            'user.last_name' => 'required|min:3|max:30',
            'user.role' => 'required',
            'user.phone' => 'required|max:14',
        ]);

        $user->update([
            $user->first_name = $request->user['first_name'],
            $user->last_name = $request->user['last_name'],
            $user->role = $request->user['role'],
            $user->phone = $request->user['phone'],
        ]);

        return response()->json($user, 201);
    }

    public function deleteSuperUser($id){
        $admin = Admin::findOrFail($id);

        $admin->delete();

        return response()->json(['message' => 'Admin Deleted'], 201);
    }

    public function toggleSuperUserStatus($id){
        $admin = Admin::findOrFail($id);

        if($admin->status == 0){
            $admin->update([
                $admin->status = 1
            ]);
        }else{
            $admin->update([
                $admin->status = 0
            ]);
        }

        return response()->json($admin->status, 200);
    }

    public function updateSuperUserPassword(Request $request, $id){
        $user = Admin::findOrFail($id);

        $this->validate($request, [
            'password.password' => 'required|min:5|max:20|confirmed',
            'password.password_confirmation' => 'required'
        ]);

        $new = $request->password['password'];
        $user->update([
            $user->password = Hash::make($new)
        ]);

        return response()->json($user, 200);
    }

    public function createSuperUser(Request $request){
        $this->validate($request, [
            'user.first_name' => 'required|min:3|max:30',
            'user.last_name' => 'required|min:3|max:30',
            'user.email' => 'required|email|unique:users,email',
            'user.phone' => 'required|max:14',
            'user.role' => 'required',
            'user.password' => 'required|min:5|max:30|confirmed',
            'user.password_confirmation' => 'required'
        ]);

        $user = new Admin;
        $user->first_name = $request->user['first_name'];
        $user->last_name = $request->user['last_name'];
        $user->email = $request->user['email'];
        $user->phone = $request->user['phone'];
        $user->role = $request->user['role'];
        $user->password = Hash::make($request->user['password']);
        $user->save();

        return response()->json($user, 201);
    }

    public function getAllBanks(){
        $banks = Bank::all();

        return response()->json($banks, 200);
    }

    public function getPgntdExperts(){
        $users = Expert::latest()->paginate(2);

        return response()->json($users, 200);
    }

    public function createNewExpert(Request $request){
        $this->validate($request, [
            'user.first_name' => 'required|min:3|max:30',
            'user.last_name' => 'required|min:3|max:30',
            'user.email' => 'required|email|unique:experts,email',
            'user.phone' => 'required|max:14',
            'user.password' => 'required|min:5|max:30|confirmed',
            'user.password_confirmation' => 'required'
        ]);

        $pool = '0123456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $id = substr(str_shuffle($pool), 0, 6);

        $user = new Expert;
        $user->first_name = $request->user['first_name'];
        $user->last_name = $request->user['last_name'];
        $user->email = $request->user['email'];
        $user->phone = $request->user['phone'];
        $user->expert_id = $id;
        $user->password = Hash::make($request->user['password']);
        $user->save();

        return response()->json($user, 201);
    }

    public function getExpert($id){
        $expert = Expert::findOrFail($id);

        return response()->json($expert, 201);
    }

    public function updateExpert(Request $request, $id){
        $validator = $this->validate($request, [
            'user.first_name' => 'required|min:3|max:30',
            'user.last_name' => 'required|min:3|max:30',
            'user.email' => 'required|email',
            'user.phone' => 'required|max:14',
            'user.account_type' => 'max:12',
            'user.account_no' => 'max:10',
            'user.account_name' => 'max:50'
        ]);

        $expert = Expert::findOrFail($id);

        $expert->update([
            $expert->first_name = $request->user['first_name'],
            $expert->last_name = $request->user['last_name'],
            $expert->email = $request->user['email'],
            $expert->phone = $request->user['phone'],
            $expert->bank_id = $request->user['bank_id'],
            $expert->account_type = $request->user['account_type'],
            $expert->account_no = $request->user['account_no'],
            $expert->account_name = $request->user['account_name']
        ]);

        return response()->json($expert, 201);
    }

    public function toggleExpertStatus($id){
        $user = Expert::findOrFail($id);

        if($user->status == 0){
            $user->update([
                $user->status = 1
            ]);
        }else{
            $user->update([
                $user->status = 0
            ]);
        }

        return response()->json($user->status, 200);
    }

    public function deleteExpert($id){
        $expert = Expert::findOrFail($id);

        $expert->delete();

        return response()->json(['message' => 'Expert Deleted'], 201);
    }

    public function changeExpertPswd($id, Request $request){
        $this->validate($request, [
            'password.password' => 'required|min:5|max:20|confirmed',
            'password.password_confirmation' => 'required'
        ]);

        $user = Expert::findOrFail($id);

        $new = $request->password['password'];
        $user->update([
            $user->password = Hash::make($new)
        ]);

        return response()->json($user, 200);
    }

    public function getExpertForecastSummary($id){
        $forecasts = ExpertPredictionSummary::where('expert_id', $id)->get();

        return response()->json($forecasts, 200);
    }

    public function adminGetSingleExpertForecasts($id){
        $preds = ExpertPrediction::where('prediction_code', $id)->get();

        return response()->json($preds, 200);
    }

    public function adminGetExpertEvent($id){
        $event = ExpertPrediction::findOrFail($id);

        return response()->json($event, 200);
    }

    public function adminChangeExpertEventStatus(Request $request, $id){
        $event = ExpertPrediction::findOrFail($id);
        $newStatus = $request->status;
        // print_r($event);

        $event->update([
            $event->status = $newStatus
        ]);

        return response()->json($event, 200);
    }

    public function adminGetExpertforecastSummary($id){
        $forecast = ExpertPredictionSummary::where('forecast_id', $id)->first();

        return response()->json($forecast, 200);
    }

    public function adminGetPgntdExpertforecasts(){
        $fc = ExpertPredictionSummary::latest()->paginate(3);

        return response()->json($fc, 200);
    }

    public function adminDeleteExpertForecast($id){
        $fc = ExpertPredictionSummary::findOrFail($id);

        $preds = ExpertPrediction::where('prediction_code', $fc->forecast_id)->get();

        foreach ($preds as $pred) {
            $pred->delete();
        }

        $fc->delete();

        return response()->json(['message' => 'Forecast deleted'], 200);
    }

    public function getPgntdCountries(){
        $countries = Country::latest()->paginate(10);

        return response()->json($countries, 200);
    }

    public function createCountry(Request $request){
        $this->validate($request, [
            'country' => 'required|min:3|max:20|unique:countries,country',
            'abbrv' => 'required|min:3|max:8|unique:countries,abbrv',
        ]);

        $filename = null;
        $file = $request->flag;
        if($file){
            $this->validate($request, [
                'flag' => 'mimes:jpeg,jpg,bmp,png,gif'
            ]);

            $pool = '0123456789abcdefghijklmnopqrstuvwxyz@&';
            $ext = $file->getClientOriginalExtension();
            $filename = substr(str_shuffle($pool), 0, 5).".".$ext;

            //save new file in folder
            $file_loc = public_path('/images/countries/'.$filename);
            // $file_loc = public_path('/images/profiles/experts/'.$filename);
            if(in_array($ext, ['jpeg', 'jpg', 'png', 'gif', 'pdf'])){
                $upload = Image::make($file)->resize(30, 50, function($constraint){
                    $constraint->aspectRatio();
                });
                $upload->sharpen(1)->save($file_loc);
            }
        }

        $country = new Country;
        $country->country = $request->country;
        $country->abbrv = $request->abbrv;
        // if($filename){
            $country->flag = $filename ? $filename : null;
        // }
        //  ? $filename : null;
        $country->save();

        return response()->json($country, 200);
    }

    public function updateCountry(Request $request, $id){
        $this->validate($request, [
            'country.country' => 'required|min:3|max:20',
            'country.abbrv' => 'required|min:3|max:8',
        ]);

        $country = Country::findOrFail($id);
        $country->update([
            $country->country = $request->country['country'],
            $country->abbrv = $request->country['abbrv'],
        ]);

        return response()->json($country, 200);
    }

    public function deleteCountry($id){
        $country = Country::findOrFail($id);

        $country->delete();

        return response()->json($country, 200);
    }

    public function deleteBank($id){
        $bank = Bank::findOrFail($id);

        $bank->delete();

        return response()->json($bank, 200);
    }

    public function updateBank(Request $request, $id){
        $this->validate($request, [
            'bank.bank' => 'required|min:3|max:30',
        ]);

        $bank = Bank::findOrFail($id);
        $bank->update([
            $bank->name = $request->bank['bank'],
        ]);

        return response()->json($bank, 200);
    }

    public function createBank(Request $request){
        $this->validate($request, [
            'bank' => 'required|min:3|max:30',
        ]);

        $bank = new Bank;
        $bank->name = $request->bank;
        $bank->save();

        return response()->json($bank, 200);
    }

    public function getAllCountries(){
        $countries = Country::all();

        return response()->json($countries, 200);
    }

    // public function getPgntdLeagues(){
    //     $lgs = League::latest()->paginate(10);

    //     return response()->json($lgs, 200);
    // }

    public function delLeague($id){
        $lg = League::findOrFail($id);

        $lg->delete();

        return response()->json(['message'=>'deleted'], 200);
    }

    public function updateLeague(Request $request, $id){

    }
}
