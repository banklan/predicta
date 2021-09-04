<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Plan;

class PlanController extends Controller
{
    public function adminGetAllPlans(){
        $plans = Plan::all();

        return response()->json($plans, 200);
    }

    public function adminDelPlan($id){
        $plan = Plan::findOrFail($id);
        $plan->delete();

        return response()->json($plan, 200);
    }

    public function updatePlan(Request $request, $id){
        $this->validate($request, [
            'plan.odd' => 'required|integer',
            'plan.price' => 'required|integer',
        ]);

        $plan = Plan::findOrFail($id);
        $plan->update([
            $plan->odd = $request->plan['odd'],
            $plan->price = $request->plan['price'] * 100,
        ]);

        return response()->json($plan, 200);
    }

    public function createPlan(Request $request){
        $this->validate($request, [
            'plan.odd' => 'required|integer|unique:plans,odd',
            'plan.price' => 'required|integer',
        ]);

        $plan = new Plan;
        $plan->odd = $request->plan['odd'];
        $plan->price = $request->plan['price'] * 100;
        $plan->save();

        return response()->json($plan, 200);
    }
}
