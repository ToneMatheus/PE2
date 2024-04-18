<?php
/* profile */

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
        dd("niet");
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
