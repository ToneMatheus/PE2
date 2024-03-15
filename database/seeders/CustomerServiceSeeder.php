<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class ExecutiveManagerSeeder extends Seeder
{
    public function run()
    {
        $encryptedRichPassword = Hash::make('secret');

        // Seed Address ID	street	number	postalCode	bus	city	region	
        $address = DB::table('address')->insertGetId([
            'street' => 'Gold land',
            'number' => 25,
            'postalCode' => 1200,
            'bus' => '2',
            'city' => 'Jette',
            'region' => 'Brussels',
        ]);

       $userID = DB::table('user')->insertGetId([
            'username' => 'Raymond',
            'password' => $encryptedRichPassword,
            'email' => 'raymond@gmail.com',
            'isActivated' => 1,
            
        ]);

        // Seed Employee
        DB::table('employee')->insert([
            'lastName' => 'Summers',
            'firstName' => 'Raymond',
            'birthDate' => now()->subYear(35),
            'hireDate' => now()->subYears(5),
            'department' => '',
            'phoneNumber' => '0478232038',
            'email' => 'raymond@gmail.com',
            'nationality' => 'American',
            'job' => 'Customer Service',
            'sex' => 'M',
            'addressID' => $address,
            'notes' => '',
            'salaryPerMonth' => 1700.55,
            'userID' => $userID,
            'isCustomer' => 0,
        ]);

        DB::table('roles')->insert([
            'roleDescription' => 'Customer support in case of questions or other concerns about our company',
            'UserID' => $userID,
            'StartDate' => now()->subYears(5),
            'EndDate' => now()->addYears(4),
        ]);
    }
}
