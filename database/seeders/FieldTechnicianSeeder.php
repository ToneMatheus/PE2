<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class FieldTechnicianSeeder extends Seeder
{
    public function run()
    {
        $encryptedRichPassword = Hash::make('secret');

        // Seed Address ID	street	number	postalCode	bus	city	region	
        $address = DB::table('address')->insertGetId([
            'street' => 'Lange Eikstraat',
            'number' => 32,
            'postalCode' => 1970,
            'bus' => '12',
            'city' => 'Wezembeek-Oppem',
            'region' => 'Flanders',
        ]);

       $userID = DB::table('user')->insertGetId([
            'username' => 'Julie',
            'password' => $encryptedRichPassword,
            'email' => 'julieVDH@gmail.com',
            'isActivated' => 1,
            
        ]);

        // Seed Employee
        DB::table('employee')->insert([
            'lastName' => 'Van De Huis',
            'firstName' => 'Julie',
            'birthDate' => now()->subYear(25),
            'hireDate' => now()->subYears(3),
            'department' => '',
            'phoneNumber' => '0478302038',
            'email' => 'julieVDH@gmail.com',
            'nationality' => 'Dutch',
            'job' => 'Field Technician',
            'sex' => 'F',
            'addressID' => $address,
            'notes' => '',
            'salaryPerMonth' => 1700.55,
            'userID' => $userID,
            'isCustomer' => 1,
        ]);

        DB::table('roles')->insert([
            'roleDescription' => 'Provides on-site and end user support concerning technical issues.',
            'UserID' => $userID,
            'StartDate' => now()->subYears(3),
            'EndDate' => now()->addYears(5),
        ]);
    }
}
