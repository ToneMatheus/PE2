<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\RoleHelper;

class LoginController extends Controller
{
    public function showLoginForm(){
        return view('login');
    }

    public function login(Request $request){
        $credentials = $request->only('username', 'password');

        if(Auth::attempt($credentials)){
            $user = Auth::user();
            $roleHelper = new RoleHelper();

            if(!$roleHelper->hasRole($user->ID, 'Customer')){
                return redirect()->intended('/test');
            } else {
                return redirect()->intended('/welcome');
            }
        }

        return redirect()->back()->withInput($request->only('username'));
    }
}

