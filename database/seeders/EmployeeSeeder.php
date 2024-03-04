<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class EmployeeSeeder extends Seeder
{
    public function run()
    {
        $encryptedRichPassword = Hash::make('RichJuul');

        // Seed Address ID	street	number	postalCode	bus	city	region	
        $address = DB::table('address')->insertGetId([
            //'ID' => 1,
            'street' => 'Dijkstraat',
            'number' => 328,
            'postalCode' => 8630,
            'bus' => '',
            'city' => 'Eggewaartskapelle',
            'region' => 'dichtbij',
        ]);

       $userID = DB::table('user')->insertGetId([
            
            //'ID' => 1,
            'username' => 'RichJuul',
            'password' => $encryptedRichPassword,
            'email' => 'JuulRichters@jourrapide.com',
            'isActivated' => 1,
            
        ]);

        // Seed Employee
        DB::table('employee')->insert([
            'lastName' => 'Richters',
            'firstName' => 'Juul',
            'birthDate' => now()->subYear(20),
            'hireDate' => now()->addYears(1),
            'department' => 'IT Department',
            'phoneNumber' => '0493229086',
            'email' => 'JuulRichters@jourrapide.com',
            'nationality' => 'Belgium',
            'job' => 'Programmer',
            'sex' => 'M',
            'addressID' => $address,
            'notes' => '',
            'salaryPerMonth' => 1200.55,
            'userID' => $userID,
            'isCustomer' => 0,
        ]);
    }
}
