<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\User;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::where('email', '=', $request->email)
        ->first();

        if($user && $user->password_reset_token === $request->token){
            $user->password = Hash::make($request->password);
            $user->password_reset_token = null;
            $user->changed_default = 1;
            $user->save();

            event(new PasswordReset($user));

            return redirect()->route('login')->with('status', 'Your password has been reset successfully. Please log in with your new password.');
        }

        return redirect()->route('password.reset', ['token' => $request->token])->withErrors(['email' => 'Invalid email or token. Please try again.']);
    }
}
