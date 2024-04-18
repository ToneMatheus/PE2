<?php
/* profile */

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

        $email = $user->email;
        $user->email = $user->getOriginal('email');
        $user->save();
        $user->email = $email;

        //TODO start mail server ga naar C:\Users\HEYVA\Downloads\mailpit-windows-amd64 (1) en voer mailpit.exe uit
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;

            $id = Crypt::encrypt($user->id);
            $emailEncrypt = Crypt::encrypt($user->email);
            $to = Crypt::encrypt("profile");

            Mail::to($user->email)->send(new ConfirmationMailRegistration($id, $emailEncrypt, $to));

            return Redirect::route('profile.edit')->with('verify_email_message', 'Please verify your email address.');
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    
    public function confirmEmail($token, $email, Request $request)
    {
        $id = Crypt::decrypt($token);
        $email = Crypt::decrypt($email);

        $user = User::find($id);

        $user->email_verified_at = now();
        $user->updated_at = now();
        $user->email = $email;

        $user->save();

        $request->session()->forget('verify_email_message');

    return redirect()->route('profile.edit')->with('status', 'Profile updated.');

    }

    // ! van hier
    /**
     * Update the user address
     */
    public function updateAddress(AddressUpdateRequest $request): RedirectResponse
    {
        dd($request->Address());

    }
    // ! tot hier mag weg

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

        return redirect()->route('profile.edit')->with('status', 'billing address is updated.');
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
