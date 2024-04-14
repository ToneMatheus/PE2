<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\RegistrationRequest;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Carbon;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    public const VALIDATION_RULES = [
        // 'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
        // 'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'username' => 'required|string|max:255|unique:users',
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'password' => 'required|string|min:8|confirmed',
        'phone_nbr' => 'required|digits:10',
    ];

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegistrationRequest $request): RedirectResponse
    {
        // dd($request);

        $userdata = [
            'username' => $request->username,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_nbr' => $request->phone_nbr,
            'birth_date' => $request->birth_date,
            'title' => $request->title,
            'is_active' => 0,
        ];

        if ($request->has('is_company')) {
            $userdata['is_company'] = 1;
            $userdata['company_name'] = $request->company_name;
        };

        $user = User::create($userdata);
        event(new Registered($user));

        dd($user);


        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    public function confirmEmail(){

    }
}
