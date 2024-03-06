<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use PHPUnit\Event\Code\Test;

class CustomerController extends Controller
{
    public function Manage(){
        return view('Customer.Manage');
    }

    public function emailValidationChangeUserInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email1' => 'required|email',
            'email2' => 'required|email|same:email1',

        ]);

        // Als validatie mislukt, stuur de gebruiker terug met de fouten
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Als validatie slaagt, verwerk de gegevens
        // Hier kun je de logica schrijven om de e-mails op te slaan of iets anders te doen

        return redirect()->back()->with('success', 'E-mail is succesvol veranderd!');
    }

    public function profileValidationChangeUserInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'FirstName' => 'required',
            'LastName' => 'required',
            'Calling' => 'required',

        ]);

        // Als validatie mislukt, stuur de gebruiker terug met de fouten
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Als validatie slaagt, verwerk de gegevens
        // Hier kun je de logica schrijven om de e-mails op te slaan of iets anders te doen

        return redirect()->back()->with('success', 'profiel success vol gewijzigd');
    }

    public function passwdValidation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // je moet minstens 8 characters hebben waarvan kleine, grote, en cijfers hebben.
            // de lengte en de regex moet ik nog testen.
            'paswdOld' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            'paswdNew1' => 'required|different:paswdOld|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            'paswdNew2' => 'required|same:paswdNew1',
        ], [
                'paswdOld.required' => 'Het oude wachtwoord is vereist.',
                'paswdNew1.required' => 'Het nieuwe wachtwoord is vereist.',
                'paswdNew1.different' => 'Het nieuwe wachtwoord mag niet hetzelfde zijn als het oude wachtwoord.',
                'paswdNew2.required' => 'Bevestiging van het nieuwe wachtwoord is vereist.',
                'paswdNew2.same' => 'De bevestiging van het nieuwe wachtwoord moet overeenkomen met het nieuwe wachtwoord.',
        ]);

        // Als validatie mislukt, stuur de gebruiker terug met de fouten
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Als validatie slaagt, verwerk de gegevens
        // Hier kun je de logica schrijven om het wachtwoord op te slaan of iets anders te doen

        return redirect()->back()->with('success', 'Wachtwoord is succesvol veranderd!');
    }

}
