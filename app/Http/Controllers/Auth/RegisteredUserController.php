<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\RegistrationRequest;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\User_Role;
use App\Models\Role;
use App\Models\Address;
use App\Models\Customer_Address;

use App\Providers\RouteServiceProvider;

use Illuminate\Auth\Events\Registered;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Validation\Rules;

use Illuminate\View\View;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;

use App\Mail\ConfirmationMailRegistration;

use App\Notifications\NewUserNotification;

// CH kijk als je paswoord niet overeenkomt met het comfirm dan wordt de mail wel opgeslagen.

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

        $userdata = [
            'username' => $request->username,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_nbr' => $request->phone_nbr,
            'birth_date' => $request->birth_date,
            'title' => $request->title,
            'is_active' => 1,
        ];


        if ($request->has('is_company')) {
            $userdata['is_company'] = 1;
            $userdata['company_name'] = $request->company_name;
        };

        if($request->has('is_landlord')){
            $userdata['is_landlord'] = 1;
        }

        if($request->nationality != ''){
            $userdata['nationality'] = $request->nationality;
        }


        $email = $request->input('email');

        if(!$this->isEmailUnique($email))
        {
        return Redirect::back()->with('top_message', 'Your email is already registerd in our system');
        }

        $user = User::create($userdata);

        $user->created_at = now()->timezone('Europe/Brussels');
        $user->updated_at = now()->timezone('Europe/Brussels');
        $user->save();

        event(new Registered($user));

        $role = Role::where('role_name', 'Customer')->first();

        $userRoleDate = [
            'user_id' => $user->id,
            'role_id' => $role->id,
        ];

        $userRole = User_Role::create($userRoleDate);

        // Notify the user
        $user->notify(new NewUserNotification());

        $id = Crypt::encrypt($user->id);
        $emailEncrypt = Crypt::encrypt($user->email);
        $origin = 'register';

        $addressDate = [
            'street' => $request->street,
            'number' => $request->number,
            'box' => $request->box,
            'postal_code' => $request->postal_code,
            'city' => $request->city,
            'province' => $request->province,
            'country' => $request->country,
            'type' => $request->type
        ];

        $address = Address::create($addressDate);

        $customerAddressData = [
            'start_date' => now()->timezone('Europe/Brussels'),
            'user_id' => $user->id,
            'address_id' => $address->id,
        ];

        Customer_Address::create($customerAddressData);

        $id = Crypt::encrypt($user->id);
        $emailEncrypt = Crypt::encrypt($user->email);
        $origin = Crypt::encrypt("register");

        Mail::to($user->email)->send(new ConfirmationMailRegistration($id, $emailEncrypt, $origin));

        // return Redirect::back()->with('top_message', 'Please verify your email address.');
        // return Redirect::route('profile.emailChanged')->with('from', 'Thanks for signing up! Before getting started');

        Auth::login($user);

        session()->put('from_tekst', 'Thanks for signing up! Before getting started');
        session()->put('from', Crypt::encrypt("register"));
        return Redirect::route('profile.emailChanged');

    }

    public function confirmEmail($token, Request $request){
        $id = Crypt::decrypt($token);

        $user = User::find($id);

        $user->email_verified_at = now()->timezone('Europe/Brussels');

        $user->save();

        session()->forget('from');
        session()->forget('from_tekst');

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    private function isEmailUnique($email)
{
    $user = User::where('email', $email)->first();

    return $user ? false : true;
}
}
