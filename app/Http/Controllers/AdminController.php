<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Admin;

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
}
