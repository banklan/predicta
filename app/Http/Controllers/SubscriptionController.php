<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Subscription;
use App\User;
use App\Expert;
use App\ExpertPredictionSummary;
use App\Earning;
use App\Payment;

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
        $sub->is_active = 1;
        $sub->save();

        $sub->fresh();
        //create an earning entry for the subscription
        $earning = new Earning;
        $earning->subscription_id = $sub->id;
        $earning->expert_id = $sub->expert_id;
        $earning->total = $sub->amount;
        $earning->exp_amount = $sub->amount * 0.5;
        $earning->admin_amount = $sub->amount * 0.5;
        $earning->is_settled = false;
        $earning->save();

        // create a payment entry for subscription
        $pay_pool = '0123456789abcdefghijklmnopqrstuvwxyz';
        $payment_id = substr(str_shuffle($pay_pool), 0, 6);
        $payment = new Payment;
        $payment->user_id = $user->id;
        $payment->subscription_id = $sub->id;
        $payment->amount = $sub->amount;
        $payment->tx_id = $payment_id;
        if($request->resp['status'] == 'success'){
            $payment->status = true;
        }else{
            $payment->status = false;
        }
        $payment->message = $request->resp['message'];
        $payment->ref_id = $request->resp['reference'];
        $payment->trans = $request->resp['trans'];
        $payment->trx_ref = $request->resp['trxref'];
        $payment->mode = 'Paystack';
        $payment->save();

        return response()->json($sub, 200);
    }

    public function getSubscription($id){
        $sub = Subscription::where('sub_id', $id)->first();

        return response()->json($sub, 200);
    }

    public function getForecastSummary($id){
        $fc = ExpertPredictionSummary::with('bookmaker_code')->where('forecast_id', $id)->first();

        return response()->json($fc, 200);
    }

    public function getMySubscriptions(){
        $me = auth('api')->user()->id;
        $subs = Subscription::where('user_id', $me)->latest()->get();

        return response()->json($subs, 200);
    }

    public function checkIfSubscribed($odd, $exp){
        $expert = Expert::where('expert_id', $exp)->first();
        $auth = auth('api')->user()->id;

        $subscriptions = Subscription::where('user_id', $auth)
                                        ->where('expert_id', $expert->id)
                                        ->where('odd_cat', $odd)
                                        ->first();
        if($subscriptions){
            return response()->json(['status' => true], 200);
        }else{
            return response()->json(['status' => false], 200);
        }
    }

    public function subscriptionCount($odd, $exp){
        $expert = Expert::where('expert_id', $exp)->first();

        $count = Subscription::where('status', true)
                                        ->where('expert_id', $expert->id)
                                        ->where('odd_cat', $odd)
                                        ->count();

        return response()->json($count, 200);
    }

    public function getHotTipExperts(){
        $sort = Expert::all()->sortByDesc('winning_rate')->values()->take(3);

        return response()->json($sort, 200);
    }
}
