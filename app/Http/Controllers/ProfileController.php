<?php
/* profile */
// TODO landlord opslaan

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\AddressUpdateRequest;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

use Illuminate\View\View;

use App\Mail\ConfirmationMailRegistration;

use App\Models\User;
use App\Models\Address;
use App\Models\Customer_Address;


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

        // TODO username na kijken dat dit uniek is en geen scheldwoorden kan zijn.

        $email = $user->email;
        $original = $user->getOriginal('email');
        $user->email = $original;
        $user->save();
        $user->email = $email;

        //START start mail server ga naar C:\Users\HEYVA\Downloads\mailpit-windows-amd64 (1) en voer mailpit.exe uit
        if ($request->user()->isDirty('email')) {
            $user->email = $original;
            $request->user()->email_verified_at = null;
            $request->user()->save();
            $user->email = $email;

            $id = Crypt::encrypt($user->id);
            $emailEncrypt = Crypt::encrypt($user->email);
            $to = Crypt::encrypt("profile");

            Mail::to($user->email)->send(new ConfirmationMailRegistration($id, $emailEncrypt, $to));

            session()->put('from_tekst', 'You have made a change of your email');
            session()->put('from', Crypt::encrypt("profile"));
            return Redirect::route('profile.emailChanged');

        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    
    public function confirmEmail($token, $email, Request $request)
    {
        $id = Crypt::decrypt($token);
        $email = Crypt::decrypt($email);

        $user = User::find($id);

        $user->email_verified_at = now()->timezone('Europe/Brussels');
        $user->updated_at = now()->timezone('Europe/Brussels');
        $user->email = $email;

        $user->save();

        $request->session()->forget('verify_email_message');

        session()->forget('from');
        session()->forget('from_tekst');

    return redirect()->route('profile.edit')->with('status', 'Profile updated.');

    }

    public function emailChanged()
    {
        $from = session('from');
        return view('auth.verify-email', compact('from'));
    }


    /**
     * Update the user billing address
     */
    public function updateBillingAddress(Request $request): RedirectResponse
    {    
        $billingAddress = $request->input('is_billing_address');
        $billingAddressObject = json_decode($billingAddress);
        $userId = Auth::id();
        $customerAddressesId = Customer_Address::where('user_id', $userId)->pluck('address_id');

        foreach ($customerAddressesId as $AddressId) {
            $adres = Address::find($AddressId);
            if($AddressId == $billingAddressObject->id)
            {
                $adres->is_billing_address = 1;
            }else{
                $adres->is_billing_address = 0;
            }

            $adres->save();
        }

        // Landlord updaten
        $is_landlord = $request->input('is_landlord');
        $user = User::find($userId);
        if($is_landlord == true){
            $user->is_landlord = 1;
        }else{
            $user->is_landlord = 0;
        }
        $user->save();

        return redirect()->route('profile.edit')->with('status', 'billing address is updated.');
    }

    /**
     * add a new address to the user
     */
    public function addAddress(AddressUpdateRequest $request): RedirectResponse
    {
        $addressRequest = [
            'street' => $request->street,
            'number' => $request->number,
            'box' => $request->box,
            'province' => $request->province,
            'city' => $request->city,
            'country' => $request->country,
            'type' => $request->type,
            'is_billing_address' => 0,
            'postal_code' => $request->postal_code,
        ];


        $userId = Auth::id();
        $address = Address::create($addressRequest);
        
        $user = User::find($userId);

        if($user->is_landlord != 1)
        {
            if($request->is_landlord == true){
                $user->is_landlord = 1;
            }else{
                $user->is_landlord = 0;
            }
        }
        $user->updated_at = now()->timezone('Europe/Brussels');

        $user->save();

        $customerAddressData = [
            'start_date' => now()->timezone('Europe/Brussels'),
            'user_id' => $user->id,
            'address_id' => $address->id,
        ];

        $test =Customer_Address::create($customerAddressData);

        return redirect()->route('profile.edit')->with('status', 'New adres has been added.');
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

        // $user->is_active =0
        // $user->save();

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
