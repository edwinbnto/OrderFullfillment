<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username'=>'required',
            'password'=>'required'
        ]);

        $user = User::where('username',$request->username)->first();

        if(!$user){
            return back()->with('error','Username not found.');
        }

        if(!Hash::check($request->password,$user->password)){
            return back()->with('error','Wrong password.');
        }

        session([
            'user'=>$user->username
        ]);

        return redirect()->route('dashboard');
    }

    public function logout()
    {
        session()->forget('user');

        return redirect()->route('signup');
    }
}