<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\AddressUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmationMailRegistration;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;
use App\Models\Address;
use App\Models\Customer_Address;
use Illuminate\Support\Facades\Session;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     * Display the user's address form.
     */
    public function edit(Request $request): View
    {
        $customerAddresses = Customer_Address::where('user_id', $request->user()->id)->get();


        $addresses = [];

        foreach($customerAddresses as $cusadr){
            $addresses[] = Address::where('id', $cusadr->address_id)->first();
            // $addresses[] = DB::table('addresses')->where('id', $cusadr->address_id)->first();
        }

        return view('profile.edit', [
            'user' => $request->user(),
            'addresses' => $addresses,
        ]);
    }

    /**
     * Update the user profile info
     */

    public function updateProfile(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        $user = $request->user();

        //TEST test dit of dit werkt. want krijg nu deze error "Trying to access array offset on value of type null" maar denk dat het komt omdat de mailserver zijn max limiet bereikt heeft
        // $user->saveWithoutEmail();

        $email = $user->email;
        $user->email = $user->getOriginal('email');
        $user->save();
        $user->email = $email;
        //TEST tot hier
            if ($user->isDirty('email')) 
            {
                Mail::to($user->email)->send(new ConfirmationMailRegistration($user));
    
                return redirect()->back()->with('status', 'Please confirm your new email address by clicking the link sent to your email.');
            }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    
    public function confirmEmail($token, Request $request)
    {
        $id = Crypt::decrypt(Session::get('id'));
        $username = Crypt::decrypt(Session::get('username'));
        $first_name = Crypt::decrypt(Session::get('first_name'));
        $last_name = Crypt::decrypt(Session::get('last_name'));
        $password = Crypt::decrypt(Session::get('password'));
        $employee_profile_id = Crypt::decrypt(Session::get('employee_profile_id'));
        $is_company = Crypt::decrypt(Session::get('is_company'));
        $company_name = Crypt::decrypt(Session::get('company_name'));
        $email = Crypt::decrypt(Session::get('email'));
        $phone_nbr = Crypt::decrypt(Session::get('phone_nbr'));
        $birth_date = Crypt::decrypt(Session::get('birth_date'));
        $is_activate = Crypt::decrypt(Session::get('is_activate'));

        Session::forget('id');
        Session::forget('username');
        Session::forget('first_name');
        Session::forget('last_name');
        Session::forget('password');
        Session::forget('employee_profile_id');
        Session::forget('is_company');
        Session::forget('company_name');
        Session::forget('email');
        Session::forget('phone_nbr');
        Session::forget('birth_date');
        Session::forget('is_activate');

        $user = User::find($id);

        if ($user) {
            $user->id = $id;
            $user->username = $username;
            $user->first_name = $first_name;
            $user->last_name = $last_name;
            $user->password = $password;
            $user->employee_profile_id = $employee_profile_id;
            $user->is_company = $is_company;
            $user->company_name = $company_name;
            $user->email = $email;
            $user->phone_nbr = $phone_nbr;
            $user->birth_date = $birth_date;
            $user->is_activate = $is_activate;

            $user->save();
        }

    return redirect()->route('profile.edit')->with('status', 'Profile updated.');

    }

    /**
     * Update the user address
     */
    public function updateAddress(AddressUpdateRequest $request): RedirectResponse
    {
        dd($request->Address());

    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    //LOOK
    /**
     * Display the user's address form.
     */
    public function test(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
