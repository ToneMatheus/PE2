<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class ManagerSeeder extends Seeder
{
    public function run()
    {
        $encryptedRichPassword = Hash::make('secret');

        // Seed Address ID	street	number	postalCode	bus	city	region	
        $address = DB::table('address')->insertGetId([
            'street' => 'Fleet street',
            'number' => 32,
            'postalCode' => 1000,
            'bus' => '2',
            'city' => 'Arlost',
            'region' => 'Flanders',
        ]);

       $userID = DB::table('user')->insertGetId([
            
            //'ID' => 1,
            'username' => 'Josh',
            'password' => $encryptedRichPassword,
            'email' => 'josh@gmail.com',
            'isActivated' => 1,
            
        ]);

        // Seed Employee
        DB::table('employee')->insert([
            'lastName' => 'Josh',
            'firstName' => 'Walters',
            'birthDate' => now()->subYear(30),
            'hireDate' => now()->subYears(7),
            'department' => 'IT Department',
            'phoneNumber' => '04938999098',
            'email' => 'josh@gmail.com',
            'nationality' => 'Polish',
            'job' => 'Program manager',
            'sex' => 'M',
            'addressID' => $address,
            'notes' => '',
            'salaryPerMonth' => 2000.55,
            'userID' => $userID,
            'isCustomer' => 0,
        ]);

        DB::table('roles')->insert([
            'roleDescription' => 'Manages the IT Department',
            'UserID' => $userID,
            'StartDate' => now()->subYears(3),
            'EndDate' => now()->addYears(5),
        ]);
    }
}
