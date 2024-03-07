<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use PHPUnit\Event\Code\Test;

class CustomerController extends Controller
{
    public function Manage(){
        return view('Customer.Manage');
    }

    public function emailValidationChangeUserInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email1' => 'required | email',
            'email2' => 'required | email | same:email1',

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

    public function passwdValidationChangeUserInfo(Request $request)
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

    public function profileValidationCreateAccount(Request $request)
    {
    //     This is an own implemented service provider for swear words
    //     validation. By arandilopez from github
    //     https://github.com/arandilopez/laravel-profane

    //     Get postal code en city name out of xml file 
        $xmlPathFlanders = storage_path('app/PostalCodes/FlandersPostalInfo20240301L72.xml');
        $xmlStringFlanders = File::get($xmlPathFlanders);
        $xmlFlanders = simplexml_load_string($xmlStringFlanders);

        $xmlPathBrussels = storage_path('app/PostalCodes/BrusselsPostalInfo20240217.xml');
        $xmlStringBrussels = File::get($xmlPathBrussels);
        $xmlBrussels = simplexml_load_string($xmlStringBrussels);

        $xmlPathWallonia = storage_path('app/PostalCodes/WalloniaMunicipality20240301.xml');
        $xmlStringWallonia = File::get($xmlPathWallonia);
        $xmlWallonia = simplexml_load_string($xmlStringWallonia);

        // store vallid postal code
        $validPostcodesFlanders = [];
        $validPostcodesBrussels = [];
        $validPostcodesWallonia = [];

        // go thru all xml elements
        foreach ($xmlFlanders->xpath('//tns:postalInfo') as $postalInfoFlanders) {
            // get objectIdentifier and sove in array
            $postcodeFlanders = (string) $postalInfoFlanders->children('com', true)->code->objectIdentifier;
            $cityFlanders = (string)$postalInfoFlanders->children('com', true)->name->spelling;
            $validPostcodesFlanders[$postcodeFlanders] = $cityFlanders;
        }

        // controller de xml structuur
        foreach ($xmlBrussels->xpath('//tns:postalInfo') as $postalInfoBrussels) {
            // get objectIdentifier and sove in array
            $postcodeBrussels = (string) $postalInfoBrussels->children('com', true)->code->objectIdentifier;
            $cityBrussels = (string)$postalInfoBrussels->children('com', true)->name->spelling;
            $validPostcodesBrussels[$postcodeBrussels] = $cityBrussels;
        }

        // controller de xml structuur
        foreach ($xmlWallonia->xpath('//tns:postalInfo') as $postalInfoWallonia) {
            // get objectIdentifier and sove in array
            $postalcodeWallonia = (string) $postalInfoWallonia->children('com', true)->code->objectIdentifier;
            $cityWallonia = (string)$postalInfoWallonia->children('com', true)->name->spelling;
            $validPostcodesWallonia[$postalcodeWallonia] = $cityWallonia;
        }

        $validator = Validator::make($request->all(), [
            'FirstName' => 'required',
            'LastName' => 'required',
            'Email' => 'required | email',
            'Username' => 'required | profane',
            'Calling' => 'required',
            'PhoneNummer' => 'nullable | regex:/^[0-9]{13}$/',
            'PaswdNew1' => 'required | min:8 | regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            'PaswdNew2' => 'required | same:PaswdNew1',
            'isCompany' => 'nullable | boolean',
            'CompanyName' => $request->get('isCompany') ? 'required' : '',
            'Street' => 'required',
            'Number' => 'required | regex:/^[0-9]$/',
            'Region' => 'required',
            'Bus' => 'nullable',
            'PostalCode' => ['required', 'regex:/^(?:\d{4})$/i', 
                function ($attribute, $value, $fail) use ($validPostcodesFlanders, $postcodeFlanders, $validPostcodesBrussels, $postcodeBrussels, $validPostcodesWallonia) {
                    if (!in_array($value, $validPostcodesFlanders) && !in_array($value, $validPostcodesBrussels) && !in_array($value, $validPostcodesWallonia))
                    {
                        $fail($attribute.' is not a valid postal code.');
                    }
                    if ($value !== $validPostcodesFlanders[$postcodeFlanders] && $value !== $validPostcodesBrussels[$postcodeBrussels] 
                    && $value !== $validPostcodesWallonia)
                    {
                        $fail($attribute.' and city do not match.');
                    }
                    },
            ],
            'City' => ['required', 
            function($attribute, $value, $fail) use ($validPostcodesFlanders, $postcodeFlanders, $validPostcodesBrussels, $postcodeBrussels, $validPostcodesWallonia, ) {
                if ($value !== $validPostcodesFlanders[$postcodeFlanders] && $value !== $validPostcodesBrussels[$postcodeBrussels] 
                && $value !== $validPostcodesWallonia)
                {
                    $fail($attribute.' and postal code do not match.');
                }
            },
            ],
            ], [
            'FirstName.required' => 'FirstName is required',
            'LastName.required' => 'LastName is required',
            'Email.required' => 'Email is required',
            'Username.required' => 'Username is required',
            'Username.profane' => 'can\'t use bad words as Username',
            'Calling.required' => 'Calling is required',
            'PaswdNew1.required' => 'PaswdNew1 is required',
            'PaswdNew1.min' => 'Password need to be at least 8 characters long',
            'PaswdNew1.regex' => 'Password need to have at least 1 uppercase letter, 1 lowercase letter and 1 number',
            'PaswdNew2.required' => 'PaswdNew2 is required',
            'isCompany.boolean' => '',
            'CompanyName.required' => 'CompanyName is because \'For company\' is checked',
            'Street.required' => 'Street is required',
            'Number.required' => 'Number is required',
            'Region.required' => 'Region is required',
            'PostalCode.required' => 'PostalCode is required',
            'City.required' => 'City is required',
            ]);

        // Als validatie mislukt, stuur de gebruiker terug met de fouten
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Als validatie slaagt, verwerk de gegevens
        // Hier kun je de logica schrijven om de e-mails op te slaan of iets anders te doen

        return redirect()->back()->with('success', 'Account created');
    }
}

