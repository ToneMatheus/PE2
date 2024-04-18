<?php


namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use PHPUnit\Event\Code\Test;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmationMailRegistration;
use League\CommonMark\Extension\Table\Table;

class CustomerController extends Controller
{
    public function Manage(){
        //LOOK verander dit als login gemaakt is.
    //    $user = DB::table('users')->where('id', 39)->first(); //TODO niet hardcoded zetten
        $user = DB::table('users')->where('id',3)->first();
        // $adres = DB::table('addresses')->where('id', 18)->first();
        $customerAdresses = DB::table('customer_addresses')->where('user_id', $user->id)->get();
        //('user_id', '=', $id)
        $adresses = [];

        foreach($customerAdresses as $cusadr){
            $adresses[] = DB::table('addresses')->where('id', $cusadr->address_id)->first();
        }
        // return view('Customer.Manage', compact('user', 'adres'));
        // return view('Customer.Manage', compact('user', 'customerAdresses', 'adres'));
        return view('Customer.Manage', compact('user', 'adresses'));
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
        //CH de database module gebruiken als validation.

        //CHange database addres link tabel wordt aangepast.

        //TODO: email verification
        //REF:https://www.youtube.com/watch?v=KiHzbVsErNo
        
    //     This is an own implemented service provider for swear words
    //     validation. By arandilopez from github
    //     https://github.com/arandilopez/laravel-profane

    /* FIX: dat het addres moet kloppen.
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
        }*/

        $isCompany = $request->get('isCompany') ? 1 : 0;
        $companyNameRules = $isCompany ? 'required' : '';


        $validator = Validator::make($request->all(), [
            'FirstName' => 'required',
            'LastName' => 'required',
            'Email' => 'required | email', // | unique:users,email',
            'Username' => 'required | profane', // | unique:users,username',
            'Calling' => 'required',
            'PhoneNummer' => 'required', //vragen of het required moet zijn.
            'PaswdNew1' => 'required | min:8 | regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            'PaswdNew2' => 'required | same:PaswdNew1',
            'isCompany' => 'nullable',
            'CompanyName' => $companyNameRules,
            'Street' => 'required',
            'Number' => 'required | regex:/^\d+$/',
            'Province' => 'required',
            'Bus' => 'required', //vragen of het required moet zijn.
            'PostalCode' => ['required', 'regex:/^(?:\d{4})$/i'],
            'City' => ['required'],
            'birthday' => 'required | date | before:-18 years | after:-150 years',
            ], [
            'FirstName.required' => 'FirstName is required',

            'LastName.required' => 'LastName is required',

            'Email.required' => 'Email is required',
            'Email.unique' => 'Email needs to be unique',

            'Username.required' => 'Username is required',
            'Username.profane' => 'can\'t use bad words as Username',
            'Username.unique' => 'Username needs to be unique',

            'Calling.required' => 'Calling is required',

            'PhoneNummer.required' => 'Phone nummer is required',

            'PaswdNew1.required' => 'PaswdNew1 is required',
            'PaswdNew1.min' => 'Password need to be at least 8 characters long',
            'PaswdNew1.regex' => 'Password need to have at least 1 uppercase letter, 1 lowercase letter and 1 number',
            'PaswdNew2.required' => 'PaswdNew2 is required',

            'CompanyName.required' => 'CompanyName is required because \'For company\' is checked',

            'Street.required' => 'Street is required',

            'Number.required' => 'Number is required',
            'Number.regex' =>'Number regex',

            'Province.required' => 'Region is required',

            'Bus.required' => 'Bus is required',

            'PostalCode.required' => 'PostalCode is required',
            'PostalCode.regex' =>'PostalCode regex',

            'City.required' => 'City is required',

            'birthday.required' => 'Birthdate is required',
            'birthday.before' => 'You need to be older. At least 18 years old'
            ]);


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Als validatie slaagt, verwerk de gegevens
        $Username = $request->input('Username');
        $FirstName = $request->input('FirstName');
        $LastName = $request->input('LastName');
        $Paswd = Hash::make($request->input('PaswdNew1'));
        $Email = $request->input('Email');
        $BD = $request->input('birthday');
        $BD = Carbon::createFromFormat('Y-m-d', $BD);
        $BD = $BD->format('Y-m-d');
        $phoneNumber = $request->input('PhoneNummer');

        // Insert gebruiker en krijg de ID van de zojuist ingevoegde gebruiker
        // ? moeten we de calling ook niet opslaan (MS of MR)
        $userID = DB::table('users')->insertGetId(['username' => $Username, 'first_name' => $FirstName, 'last_name' => $LastName, 'password' => $Paswd,
            'is_company' => $isCompany, 'email' => $Email, 'birth_date' => $BD, 'phone_nbr' => $phoneNumber, 'is_activate' => 0]);
        
        
        $CompanyName = $request->input('CompanyName');

        // Update klantgegevens met bedrijfsnaam en telefoonnummer als deze zijn ingevuld
        if ($CompanyName !=='' && $isCompany == 1){
            DB::table('users')->where('id', $userID)->update(['company_name' => $CompanyName]);
        }


// REVIEW: bus kan ook optioneel zijn.

        $Street = $request->input('Street');
        $Number = $request->input('Number');
        $Province = $request->input('Province');
        $Bus = $request->input('Bus');
        $PostalCode = $request->input('PostalCode');
        $City = $request->input('City');
        $typeHouse = $request->input('typeHouse');

        $addresID = DB::table('addresses')->select('id')->where('street', $Street)->where('number', $Number)->where('box', $Bus)->where('postal_code', $PostalCode)
        ->where('city', $City)->where('province', $Province)->where('type', $typeHouse)->first();

        if($addresID ==null){
            $addresID = DB::table('addresses')->insertGetId(['street' => $Street, 'number' => $Number, 'box' => $Bus, 'postal_code' => $PostalCode,
            'city' => $City, 'province' => $Province, 'type' => $typeHouse]);
        }
        else{
            $addresID = $addresID->id;
        }

        //DB::table('users')->where('id', $userID)->update(['address_id' => $addresID]);


// ?: Hoe worden de rollen bepaald. enkel manager kan account aan maken met aangepaste rol. anders altijd customer.
// ?:hetzelfde voor is_billing_addres norm altijd 1. manager enkel op 0 zetten.
        
//?: vragen of dit goed is. dat customer_addresses auto moet gevuld worden bij aanmaken.

        $mytime = Carbon::now()->format('Y-m-d');

        DB::table('customer_addresses')->insert(['user_id' => $userID, 'address_id' => $addresID, 'start_date' => $mytime]);

        $roleID = $request->input('userRole');

        
        DB::table('user_roles')->insert(['user_id' => $userID, 'role_id' => $roleID]);

        // send confirmation mail
        Mail::to($Email)->send(new ConfirmationMailRegistration($userID));

        //doe dit hier onder vanaf op de knop uit de mail geduuwt werd 

        // return redirect()->back()->with('success', 'Account created');
        return redirect()->route('createUser')->with('wait', 'Confurm account in mail to go further');
    }

    public function activateAccount($userID)
    {
        DB::table('users')->where('id', $userID)->update(['is_activate' => 1]);
        
        // return view('createUser')->with('success', 'Account created');
        return redirect()->route('createUser')->with('success', 'Account created');

    }
}

