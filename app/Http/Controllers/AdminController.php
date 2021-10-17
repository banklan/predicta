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
use App\Payment;
use App\Subscription;
use App\User;
use App\Enquiry;
use Image;
use Carbon\Carbon;
use App\Earning;
use App\UsersFeedback;
use Illuminate\Support\Facades\Mail;
use App\Mail\FeedbackReplyEmail;
use App\Mail\DailyTipMail;
use App\Mail\DailyTipsFromSurePredict;
use App\MailingList;
use App\DailyTipsMailing;
use App\ExpertEmailConfirmation;
use App\ExpertFollow;

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
        $users = Expert::latest()->paginate(15);

        return response()->json($users, 200);
    }

    public function createNewExpert(Request $request){
        $this->validate($request, [
            'user.first_name' => 'required|min:3|max:30',
            'user.last_name' => 'required|min:3|max:30',
            'user.username' => 'required|min:3|max:30|unique:experts,username',
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
        $user->username = $request->user['username'];
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
        $conf = ExpertEmailConfirmation::where('expert_id', $id)->first();
        if($conf){
            $conf->delete();
        }

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

        // if $newStatus == 1 || 2, update $summary->is_available to null
        //status = 0 means not played, 1 means lost and 2 means won
        foreach($all_events as $tip){
            if($tip->status === 2){
                $won[] = $tip;
            }else if($tip->status === 1){
                $lost[] = $tip;
            }
        };

        if(count($won) > 0 || count($lost) > 0){
            $summary->update([
                $summary->is_available = false
            ]);
        }

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
                $summary->prog_status = 0 //still opened
            ]);
        }

        if($newStatus == 1 || $newStatus == 2){
            $summary->update([
                $summary->is_available = false
            ]);
        }

        return response()->json($event, 200);
    }

    public function adminGetExpertforecastSummary($id){
        $forecast = ExpertPredictionSummary::where('forecast_id', $id)->first();

        return response()->json($forecast, 200);
    }

    public function adminGetPgntdExpertforecasts(){
        $fc = ExpertPredictionSummary::latest()->paginate(20);

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
        $countries = Country::latest()->paginate(20);

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
        $country->flag = $filename ? $filename : null;
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
        $lgs = League::latest()->paginate(20);

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
        $teams = Team::latest()->paginate(20);

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
            'team.team' => 'required|min:3|max:25',
            'team.abbrv' => 'required|min:3|max:12',
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
            'team.team' => 'required|min:3|max:25|unique:teams,team',
            'team.abbrv' => 'required|min:3|max:12|unique:teams,abbrv',
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
        $tips = Tip::latest()->paginate(20);
        return response()->json($tips, 200);
    }

    public function updateMarket(Request $request, $id){
        $this->validate($request, [
            'mkt.tip' => 'required|min:1|max:30',
            'mkt.abbrv' => 'required|min:1|max:15',
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
            'mkt.tip' => 'required|min:1|max:30|unique:tips,tip',
            'mkt.abbrv' => 'required|min:1|max:15|unique:tips,abbrv',
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
            $summary->tip_date = $request->tipDate;

            $summary->save();
            $summary->fresh();

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
        $tips = DailyTipsSummary::latest()->paginate(20);

        return response()->json($tips, 201);
    }

    public function getDailyTipSummary($id){
        $summ = DailyTipsSummary::findOrFail($id);

        return response()->json($summ, 201);
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

    public function getPgntdSubscriptions(){
        $subs = Subscription::latest()->paginate(15);

        return response()->json($subs, 200);
    }

    public function getSubscription($id){
        $sub = Subscription::with('payment')->where('sub_id', $id)->first();

        return response()->json($sub, 200);
    }

    public function adminDelSubscription($id){
        $sub =  Subscription::where('sub_id', $id)->first();

        $sub->delete();

        return response()->json(['message' => 'Deleted!'], 200);
    }

    public function updateExpertForecastAvail($id){
        $forecast = ExpertPredictionSummary::where('forecast_id', $id)->first();

        if($forecast->is_available == true){
            $forecast->update([
                $forecast->is_available = false
            ]);
        }else{
            $forecast->update([
                $forecast->is_available = true
            ]);
        }

        return response()->json($forecast->is_available, 200);
    }

    public function getPgntdUsers(){
        $users = User::latest()->paginate(15);

        return response()->json($users, 200);
    }

    public function updateUserStatus($id){
        $user = User::findOrFail($id);

        if($user->status == true){
            $user->update([
                $user->status = false
            ]);
        }else{
            $user->update([
                $user->status = true
            ]);
        }

        return response()->json($user->status, 200);
    }

    public function adminGetUser($id){
        $user = User::with('subscription')->findOrFail($id);

        return response()->json($user, 200);
    }

    public function updateUser(Request $request, $id){
        $validator = $this->validate($request, [
            'user.first_name' => 'required|min:3|max:30',
            'user.last_name' => 'required|min:3|max:30',
            'user.phone' => 'required|max:14',
        ]);

        $user = User::findOrFail($id);

        $user->update([
            $user->first_name = $request->user['first_name'],
            $user->last_name = $request->user['last_name'],
            $user->phone = $request->user['phone'],
        ]);

        return response()->json($user, 201);
    }

    public function adminDelUser($id){
        $user = User::findOrFail($id);

        $user->delete();

        return response()->json(['message' => 'User deleted!'], 200);
    }

    public function updateUserPassword(Request $request, $id){
        $this->validate($request, [
            'password.password' => 'required|min:5|max:20|confirmed',
            'password.password_confirmation' => 'required'
        ]);

        $user = User::findOrFail($id);

        $new = $request->password['password'];
        $user->update([
            $user->password = Hash::make($new)
        ]);

        return response()->json($user, 200);
    }

    public function adminCreateUser(Request $request){
        $this->validate($request, [
            'user.first_name' => 'required|min:3|max:30',
            'user.last_name' => 'required|min:3|max:30',
            'user.email' => 'required|email|unique:users,email',
            'user.phone' => 'required|max:14',
            'user.password' => 'required|min:5|max:30|confirmed',
            'user.password_confirmation' => 'required'
        ]);

        $user = new User;
        $user->first_name = $request->user['first_name'];
        $user->last_name = $request->user['last_name'];
        $user->email = $request->user['email'];
        $user->phone = $request->user['phone'];
        $user->password = Hash::make($request->user['password']);
        $user->save();

        return response()->json($user, 201);
    }

    public function changeSubStatus($id){
        $sub = Subscription::findOrFail($id);
        if($sub->status == 0){
            $sub->update([
                $sub->status = 1
            ]);
        }else{
            $sub->update([
                $sub->status = 0
            ]);
        }

        return response()->json($sub->status, 201);
    }

    public function getTodaysSubscriptions(){
        $subs = Subscription::whereDate('created_at', Carbon::today())->get();

        return response()->json($subs, 200);
    }

    public function getWeekPayment(){
        $payments = Payment::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();

        return response()->json($payments, 200);
    }

    public function getWeekEarnings(){
        $earnings = Earning::with('subscription')->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();

        return response()->json($earnings, 200);
    }

    public function getNewlyRegExperts(){
        $exps = Expert::whereDate('created_at', Carbon::today())->get();

        return response()->json($exps, 200);
    }

    public function getNewlyRegUsers(){
        $users = User::whereDate('created_at', Carbon::today())->get();

        return response()->json($users, 200);
    }

    public function getLatestForecasts(){
        $fcs = ExpertPredictionSummary::latest()->take(5)->get();

        return response()->json($fcs, 200);
    }

    public function getLatestDailyTips(){
        $tips = DailyTip::whereDate('created_at', Carbon::today())->get();

        return response()->json($tips, 200);
    }

    public function getAllUsersCounts(){
        $users = User::count();
        $loggedin_users = User::where('is_loggedin', true)->count();
        $disabled_users = User::where('status', false)->count();

        $exps = Expert::count();
        $loggedin_exps = Expert::where('is_loggedin', true)->count();
        $disabled_exps = Expert::where('status', false)->count();

        $admin = Admin::count();

        return response()->json(['users'=> $users,
                                 'loggedInUsers' => $loggedin_users,
                                 'disabledUsers' => $disabled_users,
                                'experts' => $exps,
                                'loggedInExperts' => $loggedin_exps,
                                'disabledExperts' => $disabled_exps,
                                'admins' => $admin], 200);
    }

    public function getDailyTipsSuccessRate(){
        $tips = DailyTipsSummary::whereDate('created_at', '!=', Carbon::today())->latest()->take(5)->get();

        return response()->json($tips, 200);
    }

    public function getDailyStats(){
        $dt = DailyTipsSummary::whereDate('created_at', Carbon::today())->first();
        if($dt){
            $dtt = $dt->event_count;
        }else{
            $dtt = 0;
        }
        // $dtcount = $dt->event_count;
        $fc = ExpertPredictionSummary::whereDate('created_at', Carbon::today())->count();
        $subs = Subscription::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $exps = Expert::whereDate('created_at', Carbon::today())->count();
        $users = User::whereDate('created_at', Carbon::today())->count();

        return response()->json(['dt'=> $dtt,
                                'ft' =>  $fc,
                                'subs' => $subs,
                                'exps' => $exps,
                                'users' => $users], 200);
    }

    public function getPgntdPayments(){
        $pymts = Payment::latest()->paginate(15);

        return response()->json($pymts, 200);
    }

    public function getSubPymtDetails($id){
        $pymt= Payment::where('tx_id', $id)->first();

        return response()->json($pymt, 200);
    }

    public function getPgntdEarnings(){
        $earnings = Earning::with('subscription')->latest()->paginate(15);

        return response()->json($earnings, 200);
    }

    public function getEarningDetail($id){
        $earning = Earning::with('subscription')->findOrFail($id);

        return response()->json($earning, 200);
    }

    public function changeEarningStatus($id){
        $earning = Earning::findOrFail($id);

        if($earning->is_settled == true){
            $earning->update([
                $earning->is_settled = false
            ]);
        }else{
            $earning->update([
                $earning->is_settled = true
            ]);
        }

        return response()->json($earning->is_settled, 200);
    }

    public function getExpertEarning($id){
        $expert = Expert::findOrFail($id);
        $earnings = Earning::where('expert_id', $expert->id)->where('is_settled', true)->get();
        $unsettled = Earning::where('expert_id', $expert->id)->where('is_settled', false)->get();

        $settled = 0;
        $outstanding = 0;
        foreach ($earnings as $earn) {
            $settled = $earn->exp_amount + $settled;
        }

        foreach ($unsettled as $uns) {
            $outstanding = $uns->exp_amount + $outstanding;
        }

        return response()->json(['settled' => $settled, 'outstanding' => $outstanding], 200);
    }

    public function getExpertSubscriptions($id){
        $subs = Subscription::where('expert_id', $id)->get();
        return response()->json($subs, 200);
    }

    public function getExpertOutstandingEarnings($id){
        $earnings = Earning::with('subscription')->where('expert_id', $id)->where('is_settled', 0)->get();

        return response()->json($earnings, 200);
    }

    public function getExpertEarnings($id){
        $earnings = Earning::where('expert_id', $id)->get();

        return response()->json($earnings, 200);
    }

    public function getSingleDailyTip($code, $id){
        $dt = DailyTip::where('id', $id)->where('tip_code', $code)->first();

        return response()->json($dt, 200);
    }

    public function adminUpdateSingleDailyTip(Request $request, $id){
        $dt = DailyTip::findOrFail($id);
        $result = $request->update['home'].'-'.$request->update['away'];
        $dt->update([
            $dt->result = $result,
            $dt->is_featured = $request->update['feature'],
            $dt->status = $request->update['status']
        ]);

        return response()->json($dt, 200);
    }

    public function getDailyTipSummaryWithCode($code){
        $summary = DailyTipsSummary::where('tip_code', $code)->first();

        return response()->json($summary, 200);
    }

    public function getPgntdInboxFeedbacks(){
        $fbks = UsersFeedback::where('user_id', '!=', 9999999)->latest()->paginate(25);

        return response()->json($fbks, 200);
    }

    public function adminGetUserFeedback($id){
        $fb = UsersFeedback::findOrFail($id);

        return response()->json($fb, 200);
    }

    public function adminGetFeedbackParent($id){
        $fb = UsersFeedback::findOrFail($id);

        return response()->json($fb, 200);
    }

    public function adminGetInboxFeedbackThread($id){
        $fb = UsersFeedback::findOrFail($id);

        $thread = UsersFeedback::where('is_parent', false)->where('parent_id', $fb->parent_id)->where('created_at', '<', $fb->created_at)->get();

        return response()->json($thread, 200);
    }

    public function updateFeedbackIsRead($id){
        $fb = UsersFeedback::findOrFail($id);
        if($fb->is_read == false){
            $fb->update([
                $fb->is_read = true
            ]);
        }

        return response()->json($fb, 200);
    }

    public function adminUserId(){
        $id = 9999999;
        return $id;
    }

    public function adminReplyFeedback(Request $request, $id){
        $this->validate($request, [
            'reply.subject' => 'required|min:3|max:60',
            'reply.body' => 'required|min:5|max:600',
        ]);

        // $msg = UsersFeedback::findOrFail($id);
        $receiver = $request->reply['receiver'];
        $fb = new UsersFeedback;
        $fb->user_id_to = $request->reply['user_id_to'];
        $fb->user_id = $this->adminUserId();
        $fb->is_parent = false;
        $fb->parent_id = $request->reply['parent_id'];
        $fb->target = $request->reply['target'];
        $fb->subject = $request->reply['subject'];
        $fb->body = $request->reply['body'];
        $fb->save();

        $fb->fresh();

        Mail::to($receiver->email)->send(new FeedbackReplyEmail($receiver, $fb));

        return response()->json(['fb' => $fb, 'to' => $receiver], 200);
    }

    public function getPgntdOutboxFeedbacks(){
        $fbks = UsersFeedback::where('user_id', 9999999)->latest()->paginate(25);

        return response()->json($fbks, 200);
    }

    public function adminDelOutboxMsg($id){
        $msg = UsersFeedback::findOrFail($id);
        $msg->delete();

        return response()->json(['message' => 'Deleted'], 200);
    }

    public function adminDelFeedbackThread($id){
        $msg = UsersFeedback::findOrFail($id);
        $parent_id = $msg->parent_id;
        $parent = UsersFeedback::findOrFail($parent_id);
        $thread = UsersFeedback::where('parent_id', $parent_id)->get();

        $msg->delete();

        foreach($thread as $fb){
            $fb->delete();
        }

        $parent->delete();
        return response()->json(['message' => 'Thread Deleted'], 200);
    }

    public function getFeedbackSearchResult(Request $request){
        $q = $request->q;
        $fbs = UsersFeedback::where('subject', 'LIKE', "%".$q."%")
                            ->orWhere('body', 'LIKE', "%".$q."%")
                            ->orWhereHas('user', function($query) use($q){
                                $query->where('first_name', 'LIKE', "%".$q."%")
                                    ->orWhere('last_name', 'LIKE', "%".$q."%")
                                    ->orWhere('email', 'LIKE', "%".$q."%");
                            })->get();

        return response()->json($fbs, 200);
    }

    public function getPgntdEnquiries(){
        $enqs = Enquiry::latest()
                    ->paginate(15);

        return response()->json($enqs, 200);
    }

    public function getUnreadEnquiryCount(){
        $count = Enquiry::where('is_read', false)->count();

        return response()->json($count, 200);
    }

    public function adminDelEnquiry($id){
        $enq = Enquiry::findOrFail($id);
        $enq->delete();

        return response()->json(['message' => 'Enquiry deleted'], 200);
    }

    public function adminGetEnquiry($id){
        $enquiry = Enquiry::findorFail($id);

        return response()->json($enquiry, 200);
    }

    public function adminReadEnquiry($id){
        $enquiry = Enquiry::findorFail($id);
        $enquiry->update([
            $enquiry->is_read = true
        ]);

        return response()->json(['message' => 'Enquiry read'], 200);
    }

    public function newFbkCount(){
        $fb = UsersFeedback::whereDate('created_at', Carbon::today())->count();

        return response()->json($fb, 200);
    }

    public function unreadEnqCount(){
        $enq = Enquiry::where('is_read', false)->count();
        return response()->json($enq, 200);
    }

    public function searchForDailyTip(Request $request){
        $q = $request->q;

        $tips = DailyTipsSummary::where('tip_code', 'LIKE', "%".$q."%")
                                  ->get();

        return response()->json($tips, 200);
    }

    public function searchForExperts(Request $request){
        $q = $request->q;

        $experts = Expert::where('expert_id', 'LIKE', "%".$q."%")
                        ->orWhere('first_name', 'LIKE', "%".$q."%")
                        ->orWhere('last_name', 'LIKE', "%".$q."%")
                        ->orWhere('username', 'LIKE', "%".$q."%")
                        ->orWhere('email', 'LIKE', "%".$q."%")
                        ->orWhere('phone', 'LIKE', "%".$q."%")
                        ->orWhere('account_name', 'LIKE', "%".$q."%")
                        ->get();

        return response()->json($experts, 200);
    }

    public function searchForUsers(Request $request){
        $q = $request->q;

        $experts = User::where('first_name', 'LIKE', "%".$q."%")
                        ->orWhere('last_name', 'LIKE', "%".$q."%")
                        ->orWhere('email', 'LIKE', "%".$q."%")
                        ->orWhere('phone', 'LIKE', "%".$q."%")
                        ->get();

        return response()->json($experts, 200);
    }

    public function searchForExpertForecasts(Request $request){
        $q = $request->q;

        $fcs = ExpertPredictionSummary::where('forecast_id', 'LIKE', "%".$q."%")
                        ->orWhere('forecast_odd', 'LIKE', "%".$q."%")
                        ->orWhereHas('expert', function($query) use($q){
                            $query->where('first_name', 'LIKE', "%".$q."%")
                                ->orWhere('last_name', 'LIKE', "%".$q."%")
                                ->orWhere('username', 'LIKE', "%".$q."%")
                                ->orWhere('expert_id', 'LIKE', "%".$q."%")
                                ->orWhere('email', 'LIKE', "%".$q."%");
                        })->get();

        return response()->json($fcs, 200);
    }

    public function searchForSubscription(Request $request){
        $q = $request->q;

        $fcs = Subscription::where('sub_id', 'LIKE', "%".$q."%")
                            ->orWhere('odd_cat', 'LIKE', "%".$q."%")
                            ->orWhereHas('user', function($query) use($q){
                                $query->where('first_name', 'LIKE', "%".$q."%")
                                    ->orWhere('last_name', 'LIKE', "%".$q."%")
                                    ->orWhere('email', 'LIKE', "%".$q."%");
                            })
                            ->orWhereHas('expert', function($query) use($q){
                                $query->where('first_name', 'LIKE', "%".$q."%")
                                    ->orWhere('last_name', 'LIKE', "%".$q."%")
                                    ->orWhere('username', 'LIKE', "%".$q."%")
                                    ->orWhere('expert_id', 'LIKE', "%".$q."%")
                                    ->orWhere('email', 'LIKE', "%".$q."%");
                            })->get();

        return response()->json($fcs, 200);
    }

    public function getAllSubscriptions(){
        $subs = Subscription::all();

        return response()->json($subs, 200);
    }

    public function searchForPayments(Request $request){
        $q = $request->q;

        $pymts = Payment::where('tx_id', 'LIKE', "%".$q."%")
                        ->orWhere('amount', 'LIKE', "%".$q."%")
                        ->orWhereHas('user', function($query) use($q){
                            $query->where('first_name', 'LIKE', "%".$q."%")
                                ->orWhere('last_name', 'LIKE', "%".$q."%")
                                ->orWhere('email', 'LIKE', "%".$q."%");
                        })
                        ->orWhereHas('subscription', function($query) use($q){
                            $query->where('sub_id', 'LIKE', "%".$q."%")
                                ->orWhere('odd_cat', 'LIKE', "%".$q."%");
                        })->get();

        return response()->json($pymts, 200);
    }

    public function searchForEarnings(Request $request){
        $q = $request->q;

        $pymts = Earning::with('subscription')->where('total', 'LIKE', "%".$q."%")
                        ->orWhere('exp_amount', 'LIKE', "%".$q."%")
                        ->orWhere('admin_amount', 'LIKE', "%".$q."%")
                        ->orWhereHas('subscription', function($query) use($q){
                            $query->where('sub_id', 'LIKE', "%".$q."%")
                                ->orWhere('odd_cat', 'LIKE', "%".$q."%")
                                ->orWhere('amount', 'LIKE', "%".$q."%");
                        })->get();

        return response()->json($pymts, 200);
    }

    public function updateAdminProfile(Request $request){
        $this->validate($request, [
            'admin.f_name' => 'required|min:3|max:30',
            'admin.l_name' => 'required|min:3|max:30',
            'admin.phone' => 'required|min:8|max:14',
        ]);

        $admin = auth('admin-api')->user();
        $admin->update([
            $admin->first_name = $request->admin['f_name'],
            $admin->last_name = $request->admin['l_name'],
            $admin->phone = $request->admin['phone'],
        ]);

        return response()->json($admin, 200);
    }

    public function confirmCurrentPassword(Request $request){
        $user = auth('admin-api')->user();
        $current = $user->password;
        $check = Hash::check($request->password, $current);

        return response()->json($check, 200);
    }

    public function updateAdminProfilePassword(Request $request){
        $this->validate($request, [
            'password' => 'required|min:5|max:20|confirmed',
            'password_confirmation' => 'required'
        ]);

        $user = auth('admin-api')->user();
        $new = $request->password;

        $user->update([
            $user->password = Hash::make($new)
        ]);

        return response()->json(['message' => 'Password changed successfully']);
    }

    public function updateAdminProfilePicture(Request $request){
        $this->validate($request, [
            'image' => 'mimes:jpeg,jpg,bmp,png,gif'
        ]);

        $user = auth('admin-api')->user();
        // check if picture exists for profile, then unlink
        $old_pic = $user->picture;
        if($old_pic){
            $filePath = public_path('/images/profiles/admins/'.$old_pic);
            if(file_exists($filePath)){
                unlink($filePath);
            }
        }

        // save file in folder...later in s3 when ready to deploy
        $file = $request->image;
        if($file){
            $pool = '0123456789abcdefghijklmnopqrstuvwxyz';
            $ext = $file->getClientOriginalExtension();
            $filename = substr(str_shuffle($pool), 0, 6).".".$ext;

            //save new file in folder
            $file_loc = public_path('/images/profiles/admins/'.$filename);
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

    public function getMailingList(){
        $list = MailingList::paginate(15);

        return response()->json($list, 200);
    }

    public function toggleMailingListStatus($user){
        $user = MailingList::findOrFail($user);

        if($user->status == 1){
            $user->update([
                $user->status = false
            ]);
        }else{
            $user->update([
                $user->status = true
            ]);
        }

        return response()->json($user, 200);
    }

    public function adminAddToMailingList(Request $request){
        $this->validate($request, [
            'mail.f_name' => 'required|min:3|max:30',
            'mail.l_name' => 'required|min:3|max:30',
            'mail.email' => 'required|email|unique:mailing_list,email'
        ]);

        $ml = new MailingList;
        $ml->f_name = $request->mail['f_name'];
        $ml->l_name = $request->mail['l_name'];
        $ml->email = $request->mail['email'];
        $ml->save();

        return response()->json($ml, 200);
    }

    public function delUserFromMailingList($user){
        $user = MailingList::findOrFail($user);
        $user->delete();
        return response()->json(['message' => 'User deleted'], 200);
    }

    public function checkDailyTipMailing($id){
        $mailing = DailyTipsMailing::where('daily_tips_summary_id', $id)->count();

        return response()->json($mailing, 200);
    }

    public function mailDailyTips($id){
        $dailyTip = DailyTipsSummary::findOrFail($id);
        $pd = $dailyTip->tip_code;
        $tips = DailyTip::where('tip_code', $pd)->where('is_featured', true)->get();
        $subscribers = MailingList::all();
        foreach($subscribers as $user){
            Mail::to($user->email)->send(new DailyTipsFromSurePredict($tips, $user, $dailyTip));
        }

        $mail = new DailyTipsMailing;
        $mail->daily_tips_summary_id = $id;
        $mail->save();
        return response()->json(['message'=> 'Mails sent'], 200);
    }

    public function getMailedTips(){
        $mails = DailyTipsMailing::with('daily_tips_summary')->latest()->paginate(15);

        return response()->json($mails, 200);
    }

    public function deleteMailedDailyTip($id){
        $mail = DailyTipsMailing::findOrFail($id);

        $mail->delete();
        return response()->json(['message' => 'Deleted'], 200);
    }

    public function createBulkMarkets(Request $request){
        $mkts = $request->markets;

        $data = ['data' => $mkts];
        $validator = Validator::make($data, [
            'data.*.tip' => 'required|min:1|max:30|unique:tips,tip',
            'data.*.abbrv' => 'required|min:1|max:15|unique:tips,abbrv',
        ]);

        if($validator->fails()){
            return response()->json(['message' => 'validation failed'], 500);
        }else{
            foreach($mkts as $mkt){
                $tip = new Tip;
                $tip->tip = $mkt['tip'];
                $tip->abbrv = $mkt['abbrv'];
                $tip->save();
            }
            return response()->json(['message' => 'Created successfully'], 201);
        }
    }

    public function createBulkTeams(Request $request){
        $teams = $request->teams;

        $data = ['data' => $teams];
        $validator = Validator::make($data, [
            'data.*.team' => 'required|min:3|max:25|unique:teams,team',
            'data.*.abbrv' => 'required|min:3|max:12|unique:teams,abbrv',
        ]);

        if($validator->fails()){
            return response()->json(['message' => 'validation failed'], 500);
        }else{
            foreach($teams as $team){
                $tm = New Team;
                $tm->league_id = $team['league']['id'];
                $tm->team = $team['team'];
                $tm->abbrv = $team['abbrv'];
                $tm->save();
            }
            return response()->json(['message' => 'Teams created successfully'], 201);
        }
    }

    public function getPaginatedFollows(){
        $follows = ExpertFollow::latest()->paginate(20);

        return response()->json($follows, 200);
    }

    public function getUserFollows($user){
        $follows = ExpertFollow::where('user_id', $user)->count();

        return response()->json($follows, 200);
    }

    public function getExpertFollows($expert){
        $follows = ExpertFollow::where('expert_id', $expert)->count();

        return response()->json($follows, 200);
    }

    public function delFollow($id){
        $follow = ExpertFollow::findOrFail($id);
        if($follow){
            $follow->delete();
            return response()->json(['message' => 'Follow deleted!'], 200);
        }
    }

    public function getAllExperts(){
        $experts = Expert::all();

        return response()->json($experts, 200);
    }

    public function filterFollowsByExpert($exp){
        $follows = ExpertFollow::where('expert_id', $exp)->paginate(15);
        return response()->json($follows, 200);
    }

    public function createBookmakerWithoutLogo(Request $request){
        $this->validate($request, [
            'bkmk' => 'required|min:3|max:12|unique:bookmakers,name',
        ]);

        $bkmr = new Bookmaker;
        $bkmr->name = $request->bkmk;
        $bkmr->save();

        return response()->json($bkmr, 200);
    }
}
