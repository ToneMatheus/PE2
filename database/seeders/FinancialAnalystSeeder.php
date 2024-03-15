<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class FinancialAnalystSeeder extends Seeder
{
    public function run()
    {
        $encryptedRichPassword = Hash::make('secret');

        // Seed Address ID	street	number	postalCode	bus	city	region	
        $address = DB::table('address')->insertGetId([
            'street' => 'Main road street',
            'number' => 22,
            'postalCode' => 1900,
            'bus' => '12',
            'city' => 'Oostende',
            'region' => 'Flanders',
        ]);

       $userID = DB::table('user')->insertGetId([
            'username' => 'Holly',
            'password' => $encryptedRichPassword,
            'email' => 'holly@gmail.com',
            'isActivated' => 1,
            
        ]);

        // Seed Employee
        DB::table('employee')->insert([
            'lastName' => 'Pitts',
            'firstName' => 'Holly',
            'birthDate' => now()->subYear(20),
            'hireDate' => now()->subYears(6),
            'department' => '',
            'phoneNumber' => '0478302089',
            'email' => 'holly@gmail.com',
            'nationality' => 'British',
            'job' => 'Financial Analyst',
            'sex' => 'F',
            'addressID' => $address,
            'notes' => '',
            'salaryPerMonth' => 1100.55,
            'userID' => $userID,
            'isCustomer' => 1,
        ]);

        DB::table('roles')->insert([
            'roleDescription' => 'Responsible for the undertaking of financial analysis within the company',
            'UserID' => $userID,
            'StartDate' => now()->subYears(6),
            'EndDate' => now()->addYears(1),
        ]);
    }
}
