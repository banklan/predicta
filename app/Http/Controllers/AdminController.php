<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Admin;
use App\Bank;
use App\Expert;
use App\ExpertPrediction;
use App\ExpertPredictionSummary;
use App\Country;
use App\League;
use App\Team;
use App\Bookmaker;
use App\DailyTip;
use App\Tip;
use App\DailyTipsSummary;
use App\InternationalCompetition;
use App\NationalTeam;
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

        $event->update([
            $event->status = $newStatus
        ]);

        $all_events = ExpertPrediction::where('prediction_code', $event->prediction_code)->get();
        $summary = ExpertPredictionSummary::where('forecast_id', $event->prediction_code)->first();
        $won = [];
        $lost = [];
        //status = 0 means not played, 1 means lost and 2 means won
        foreach($all_events as $tip){
            if($tip->status === 2){
                $won[] = $tip;
            }else if($tip->status === 1){
                $lost[] = $tip;
            }
        };

        if($all_events->count() === count($won)){
            $summary->update([
                $summary->prog_status = 2 //won
            ]);
        }else if(count($lost) > 0){
            $summary->update([
                $summary->prog_status = 1 //lost
            ]);
        }else{
            $summary->update([
                $summary->prog_status = 0 //still running
            ]);
        }

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

    public function getPgntdLeagues(){
        $lgs = League::latest()->paginate(10);

        return response()->json($lgs, 200);
    }

    public function delLeague($id){
        $lg = League::findOrFail($id);

        $lg->delete();

        return response()->json(['message'=>'deleted'], 200);
    }

    public function updateLeague(Request $request, $id){
        $this->validate($request, [
            'league.league' => 'required|min:3|max:15',
            'league.abbrv' => 'required|min:3|max:8',
            'league.country' => 'required|numeric',
        ]);

        $league = League::findOrFail($id);
        $league->update([
            $league->league = $request->league['league'],
            $league->abbrv = $request->league['abbrv'],
            $league->country_id = $request->league['country'],
        ]);

        return response()->json($league, 200);
    }

    public function createLeague(Request $request){
        $this->validate($request, [
            'league.league' => 'required|min:3|max:15',
            'league.abbrv' => 'required|min:3|max:8',
            'league.country' => 'required|numeric',
        ]);

        $lg = New League;
        $lg->country_id = $request->league['country'];
        $lg->league = $request->league['league'];
        $lg->abbrv = $request->league['abbrv'];
        $lg->save();
        $lg = $lg->fresh();

        return response()->json($lg, 200);
    }

    public function getPgntdTeams(){
        $teams = Team::latest()->paginate(10);

        return response()->json($teams, 200);
    }

    public function getAllLeagues(){
        $lgs = League::all();

        return response()->json($lgs, 200);
    }

    public function deleteTeam($id){
        $team = Team::findOrFail($id);

        $team->delete();

        return response()->json(['message'=>'deleted'], 200);
    }

    public function updateTeam(Request $request, $id){
        $this->validate($request, [
            'team.team' => 'required|min:3|max:20',
            'team.abbrv' => 'required|min:3|max:8',
            'team.league' => 'required|numeric',
        ]);

        $team = Team::findOrFail($id);
        $team->update([
            $team->team = $request->team['team'],
            $team->abbrv = $request->team['abbrv'],
            $team->league_id = $request->team['league'],
        ]);

        return response()->json($team, 200);
    }

    public function createNewTeam(Request $request){
        $this->validate($request, [
            'team.team' => 'required|min:3|max:20|unique:teams,team',
            'team.abbrv' => 'required|min:3|max:8|unique:teams,abbrv',
            'team.league' => 'required|numeric',
        ]);

        $team = New Team;
        $team->league_id = $request->team['league'];
        $team->team = $request->team['team'];
        $team->abbrv = $request->team['abbrv'];
        $team->save();
        $team = $team->fresh();

        return response()->json($team, 200);
    }

    public function filterTeamsByLeague($id){
        $teams = Team::where('league_id', $id)->get();
        return response()->json($teams, 200);
    }

    public function getLeague($id){
        $lg = League::findOrFail($id);
        return response()->json($lg, 200);
    }

    public function searchForTeams(Request $request){
        $q = $request->q;

        $teams = Team::where('team', 'LIKE', "%".$q."%")
        ->orWhere('abbrv', 'LIKE', "%".$q."%")
        ->get();

        return response()->json($teams, 200);
    }

    public function getBookmakers(){
        $bkm = Bookmaker::all();

        return response()->json($bkm, 200);
    }

    public function createNewBookmaker(Request $request){
        $this->validate($request, [
            'name' => 'required|min:3|max:12|unique:bookmakers,name',
            'logo' => 'required|mimes:jpeg,jpg,bmp,png,gif',
        ]);

        // $logoname = null;
        $file = $request->logo;
        if($file){
            $pool = '0123456789abcdefghijklmnopqrstuvwxyz@&';
            $ext = $file->getClientOriginalExtension();
            $filename = substr(str_shuffle($pool), 0, 4).".".$ext;

            //save logo in folder
            $file_loc = public_path('/images/bookmakers/'.$filename);
            if(in_array($ext, ['jpeg', 'jpg', 'png', 'gif', 'pdf'])){
                $upload = Image::make($file)->resize(70, 120, function($constraint){
                    $constraint->aspectRatio();
                });
                $upload->sharpen(1)->save($file_loc);
            }
        }

        $bkmr = new Bookmaker;
        $bkmr->name = $request->name;
        $bkmr->logo = $filename;
        $bkmr->save();

        return response()->json($bkmr, 200);
    }

    public function updateBookmaker(Request $request, $id){
        $this->validate($request, [
            'bkm.name' => 'required|min:3|max:12',
        ]);

        $bkm = Bookmaker::findOrFail($id);

        $bkm->update([
            $bkm->name = $request->bkm['name']
        ]);

        return response()->json($bkm, 200);
    }

    public function deleteBookmaker($id){
        $bkm = Bookmaker::findOrFail($id);

        // remove file
        $logo = $bkm->logo;
        if($logo){
            $filePath = public_path('/images/bookmakers/'.$logo);
            if(file_exists($filePath)){
                unlink($filePath);
            }
        }
        $bkm ->delete();

        return response()->json(['message' => 'Deleted!'], 200);
    }

    public function getPgntdMarkets(){
        $tips = Tip::latest()->paginate(10);
        return response()->json($tips, 200);
    }

    public function updateMarket(Request $request, $id){
        $this->validate($request, [
            'mkt.tip' => 'required|min:3|max:20',
            'mkt.abbrv' => 'required|min:3|max:8',
        ]);

        $mkt = Tip::findOrFail($id);

        $mkt->update([
            $mkt->tip = $request->mkt['tip'],
            $mkt->abbrv = $request->mkt['abbrv'],
        ]);

        return response()->json($mkt, 200);
    }

    public function deleteMarket($id){
        $mkt = Tip::findOrFail($id);

        $mkt ->delete();

        return response()->json(['message' => 'Deleted!'], 200);
    }


    public function createNewMarket(Request $request){
        $this->validate($request, [
            'mkt.tip' => 'required|min:3|max:20|unique:tips,tip',
            'mkt.abbrv' => 'required|min:3|max:8|unique:tips,abbrv',
        ]);

        $mkt = new Tip;
        $mkt->tip = $request->mkt['tip'];
        $mkt->abbrv = $request->mkt['abbrv'];
        $mkt->save();

        return response()->json($mkt, 200);
    }

    public function getTeamsForALeague($id){
        $teams = Team::where('league_id', $id)->get();

        return response()->json($teams, 200);
    }

    public function getAllMarkets(){
        $mkts = Tip::all();
        return response()->json($mkts, 200);
    }

    public function getLeaguesForCountry($id){
        $lgs = League::where('country_id', $id)->get();
        return response()->json($lgs, 200);
    }

    public function createDailyTips(Request $request){
        $tips = $request->tips;

        $data = ['data' => $tips];
        // print_r($data);
        $validator = Validator::make($data, [
            'data.*.country' => 'min:3|max:22',
            'data.*.league' => 'required|min:3|max:22',
            'data.*.odd' => 'required|numeric|between:1,100',
            'data.*.home' => 'required|min:3|max:22',
            'data.*.away' => 'required|min:3|max:22',
            'data.*.tip' => 'required|min:1|max:15',
            'data.*.date' => 'required',
            'data.*.time' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['message' => 'validation failed'], 500);
        }else{
            $tip_code = bin2hex(random_bytes(5));
            $admin_id = auth('admin-api')->user()->id;

            // create summary
            $summary = new DailyTipsSummary;
            $summary->admin_id = $admin_id;
            $summary->tip_code = $tip_code;
            $summary->event_count = count($tips);
            $summary->status = 0;

            $summary->save();

            foreach($tips as $tip){
                $pred = new DailyTip;
                $pred->admin_id = $admin_id;
                $pred->daily_tips_summary_id = $summary->id;
                $pred->tip_code = $tip_code;
                $pred->country = $tip['country'];
                $pred->league = $tip['league'];
                $pred->home = $tip['home'];
                $pred->away = $tip['away'];
                $pred->tip = $tip['tip'];
                $pred->odd = $tip['odd'];
                $pred->event_date = $tip['date'];
                $pred->event_time = $tip['time'];
                $pred->status = 0;

                $pred->save();
            }
        }
        return response()->json($summary, 201);
    }

    public function getPgntdDailyTips(){
        $tips = DailyTipsSummary::latest()->paginate(10);

        return response()->json($tips, 201);
    }

    public function getDailyTipSummary($id){
        $summ = DailyTipsSummary::findOrFail($id);

        return response()->json($summ, 201);
    }

    public function adminChangeDailyTipsStatus(Request $request, $id){
        $event = DailyTip::findOrFail($id);
        $newStatus = $request->status['newStatus'];
        $isFeatured = $request->status['isFeatured'];

        $event->update([
            $event->status = $newStatus,
            $event->is_featured = $isFeatured,
        ]);

        if($newStatus !== 0){
            $event->update([
                $event->is_open = false
            ]);
        }

        return response()->json($event, 200);
    }

    public function removeEventFromDailyTips($id){
        // print_r($id);
        $event = DailyTip::findOrFail($id);

        $tc = $event->tip_code;
        $summary = DailyTipsSummary::where('tip_code', $tc)->first();
        $summary->update([
            $summary->event_count--
        ]);

        $event->delete();

        return response()->json($summary, 200);
    }

    public function deleteDailyTips($tc){
        $summary = DailyTipsSummary::where('tip_code', $tc)->first();
        $summary->delete();

        $tips = DailyTip::where('tip_code', $tc)->get();
        foreach($tips as $tip){
            $tip->delete();
        }

        return response()->json(['message' => 'Deleted!'], 200);
    }

    public function addToDailyTips(Request $request, $tc){
        $this->validate($request, [
            'tip.country' => 'min:3|max:22',
            'tip.league' => 'required|min:3|max:22',
            'tip.odd' => 'required|numeric|between:1,100',
            'tip.home' => 'required|min:3|max:22',
            'tip.away' => 'required|min:3|max:22',
            'tip.tip' => 'required|min:1|max:15',
            'tip.date' => 'required',
            'tip.time' => 'required',
        ]);

        $summary =  DailyTipsSummary::where('tip_code', $tc)->first();
        $admin_id = auth('admin-api')->user()->id;

        $summary->update([
            $summary->event_count++
        ]);

        $tip = new DailyTip;
        $tip->admin_id = $admin_id;
        $tip->daily_tips_summary_id = $summary->id;
        $tip->tip_code = $summary->tip_code;
        $tip->country = $request->tip['country'];
        $tip->league = $tip['league'];
        $tip->home = $request->tip['home'];
        $tip->away = $request->tip['away'];
        $tip->tip = $request->tip['tip'];
        $tip->odd = $request->tip['odd'];
        $tip->event_date = $request->tip['date'];
        $tip->event_time = $request->tip['time'];
        $tip->status = 0;

        $tip->save();
        $summary = $summary->fresh();
        return response()->json($summary, 201);
    }

    public function changeIsFeaturedOfDailyTip($id){
        $summary =  DailyTipsSummary::findOrFail($id);

        if($summary->is_featured == true){
            $summary->update([
                $summary->is_featured = false
            ]);
        }else{
            $summary->update([
                $summary->is_featured = true
            ]);
        }

        return response()->json($summary, 200);
    }

    public function deleteDailyTipSummary($code){
        $summary =  DailyTipsSummary::where('tip_code', $code)->first();

        $summary->delete();

        $tips = DailyTip::where('tip_code', $code)->get();

        foreach($tips as $tip){
            $tip->delete();
        }

        return response()->json(['message' => 'Deleted!'], 200);
    }

    public function getIntnlCompetitions(){
        $comps = InternationalCompetition::all();

        return response()->json($comps, 200);
    }

    public function getNationalTeams(){
        $teams = Country::all();

        return response()->json($teams, 200);
    }
}
