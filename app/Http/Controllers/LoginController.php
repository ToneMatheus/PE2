<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Helpers\RoleHelper;

class LoginController extends Controller
{
    function index(){
        return view("login");
    }

    function logout(){        
        Auth::logout();
        return view("login");
    }

    public function login(Request $request){
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        

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
