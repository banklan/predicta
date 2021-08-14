<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Subscription;
use App\User;
use App\Expert;

class SubscriptionController extends Controller
{
    public function subscribeToExpertTips(Request $request){
        $this->validate($request, [
            'odd' => 'required|integer',
            'expert' => 'required',
        ]);

        $odd = $request->odd;
        $expt = $request->expert;
        $amount = 0;
        if($odd == 3){
            $amount = 300000;
        }else if($odd == 5){
            $amount = 500000;
        }else if($odd == 10){
            $amount = 1000000;
        }

        $user =  auth('api')->user();
        $expert = Expert::where('expert_id', $expt)->first();
        // $expiry = Carbon::parse($this->created_at)->addHours(6)->format('F jS, Y H:i:s');
        // $expiry = Carbon::now()->addDays(7);
        $expiry = Carbon::now()->addDays(7)->format('Y-m-d H:i:s');
        $pool = '0123456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $sub_id = substr(str_shuffle($pool), 0, 8);

        $sub = new Subscription;
        $sub->sub_id = $sub_id;
        $sub->user_id = $user->id;
        $sub->expert_id = $expert->id;
        $sub->odd_cat = intval($odd);
        $sub->amount = $amount;
        $sub->expiry = $expiry;
        $sub->status = 1;
        $sub->save();

        return response()->json($sub, 200);
    }

    public function getSubscription($id){
        $sub = Subscription::where('sub_id', $id)->first();

        return response()->json($sub, 200);
    }
}
