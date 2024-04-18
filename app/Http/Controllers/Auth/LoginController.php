<?php

// namespace App\Http\Controllers\Auth;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
// use App\Helpers\RoleHelper;

// class LoginController extends Controller
// {
//     public function showLoginForm(){
//         return view('login');
//     }

//     public function login(Request $request){
//         $credentials = $request->only('username', 'password');

//         if(Auth::attempt($credentials)){
//             $user = Auth::user();
//             $roleHelper = new RoleHelper();

//             if(!$roleHelper->hasRole($user->ID, 'Customer')){
//                 //Employee Page
//                 return redirect()->intended('/test');
//             } else {
//                 //Customer Page
//                 return redirect()->intended('/welcome');
//             }
//         }

//         return redirect()->back()->withInput($request->only('username'));
//     }
// }


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show the login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        // Validate the form data
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to log the user in
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect authenticated users to the intended destination
            return redirect()->intended('/home');
        }

        // If the login attempt fails, redirect back with an error message
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        return redirect('/');
    }
}