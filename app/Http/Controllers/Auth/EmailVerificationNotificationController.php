<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmationMailRegistration;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        // dd("notify controller");
        // if ($request->user()->hasVerifiedEmail()) {
        //     return redirect()->intended(RouteServiceProvider::HOME);
        // }

        // dd($request);
        $user = $request->user();

        $id = Crypt::encrypt($user->id);
        $emailEncrypt = Crypt::encrypt($user->email);
        $to = session('from');

        Mail::to($user->email)->send(new ConfirmationMailRegistration($id, $emailEncrypt, $to));

        // $request->user()->sendEmailVerificationNotification();

        // dd("test");

        return back()->with('status', 'verification-link-sent');
    }
}
