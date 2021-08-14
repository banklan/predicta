<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserEmailConfirmation;
use App\User;

class UserController extends Controller
{
    public function confirmEmail(Request $request){
        $token = $request->token;

        $conf = UserEmailConfirmation::where('token', $token)->first();
        if($conf){
            if($conf->is_confirmed){
                return response()->json(['message' => 'confirmed already'], 202);
            }else{
                $conf->update([
                    $conf->is_confirmed = true
                ]);

                 // get user and update status
                $user = User::findOrFail($conf->user_id);
                $user->update([
                    $user->status = true
                ]);

                return response()->json(['message' => 'Verified'], 200);
            }
        }
    }
}
