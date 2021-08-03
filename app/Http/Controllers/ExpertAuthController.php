<?php

namespace App\Http\Controllers;

use App\ExpertEmailConfirmation;
use App\Expert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\ExpertWelcomeEmail;

class ExpertAuthController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:expert-api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth('expert-api')->attempt($credentials)) {
            //create a table and log in failed attempts, when count is >2, lock user
            return response()->json(['error' => 'Invalid credentials'], 441);
        }

        if(auth('expert-api')->user()->status == 0){
            return response()->json(['error' => 'Unauthorized'], 551);
        }

        $user = auth('expert-api')->user();
        if($user->status == 1){
            $user->update([
                $user->is_loggedin = true
            ]);
            return $this->respondWithToken($token);
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    protected function respondWithToken($token)
    {
        $user = auth('expert-api')->user();

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => auth('expert-api')->user(),
            'expires_in' => auth('expert-api')->factory()->getTTL() * 60
        ]);
    }

    public function register(Request $request){
        $this->validate($request, [
            'expert.first_name' => 'required|min:3|max:30',
            'expert.last_name' => 'required|min:3|max:30',
            'expert.username' => 'required|min:3|max:30|unique:experts,username',
            'expert.email' => 'required|email|unique:experts,email',
            'expert.phone' => 'required|max:14',
            'expert.password' => 'required|min:5|max:30|confirmed',
            'expert.password_confirmation' => 'required'
        ]);
            // create unique expert id
        $pool = '0123456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $id = substr(str_shuffle($pool), 0, 6);

        $expert = new Expert;
        $expert->expert_id = $id;
        $expert->first_name = $request->expert['first_name'];
        $expert->last_name = $request->expert['last_name'];
        $expert->username = $request->expert['username'];
        $expert->email = $request->expert['email'];
        $expert->phone = $request->expert['phone'];
        $expert->status = 0;
        $expert->password = Hash::make($request->expert['password']);
        $expert->save();

        if($expert){
            $conf = new ExpertEmailConfirmation;
            $conf->expert_id = $expert->id;
            $conf->token = bin2hex(random_bytes(80));
            $conf->save();

            // send welcome email
            Mail::to($expert->email)->send(new ExpertWelcomeEmail($expert, $conf));
        }
            return response()->json($expert);
    }

    public function logout()
    {
        $user = auth('expert-api')->user();
        $user->update([
            $user->is_loggedin = false
        ]);

        auth('expert-api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    protected function guard()
    {
        return Auth::guard('expert-api');
    }
}
