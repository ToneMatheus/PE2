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
            'street' => 'Rue du Royaume',
            'number' => 2,
            'postalCode' => 1004,
            'bus' => '12',
            'city' => 'Anderlecht',
            'region' => 'Brussels',
        ]);

       $userID = DB::table('user')->insertGetId([
            'username' => 'Ming',
            'password' => $encryptedRichPassword,
            'email' => 'jming@gmail.com',
            'isActivated' => 1,
            
        ]);

        // Seed Employee
        DB::table('employee')->insert([
            'lastName' => 'Na wen',
            'firstName' => 'Ming',
            'birthDate' => now()->subYear(45),
            'hireDate' => now()->subYears(10),
            'department' => '',
            'phoneNumber' => '0478322038',
            'email' => 'ming@gmail.com',
            'nationality' => 'Chinese',
            'job' => 'Executive manager',
            'sex' => 'M',
            'addressID' => $address,
            'notes' => '',
            'salaryPerMonth' => 2500.55,
            'userID' => $userID,
            'isCustomer' => 0,
        ]);

        DB::table('roles')->insert([
            'roleDescription' => 'He oversees a company\'s developmental, strategic and financial decisions.',
            'UserID' => $userID,
            'StartDate' => now()->subYears(10),
            'EndDate' => now()->addYears(3),
        ]);
    }
}
