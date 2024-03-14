<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        // Seed Users
        $userId = DB::table('user')->insertGetId([
            'username' => 'john_doe',
            'password' => bcrypt('password'),
            'email' => 'john@example.com',
            'isActivated' => 1,
        ]);

        // Seed Roles
        DB::table('roles')->insert([
            'roleDescription' => 'Admin',
            'userID' => $userId,
            'startDate' => now(),
            'endDate' => now()->addYears(1),
        ]);

        // Seed Customers
        $cstID = DB::table('customer')->insertGetId([
            'lastName' => 'Smith',
            'firstName' => 'John',
            'phoneNumber' => '123456789',
            'companyName' => 'ABC Inc.',
            'isCompany' => 1,
            'userID' => $userId,
        ]);

        // Seed Addresses
        $addrID = DB::table('address')->insertGetId([
            'street' => '123 Main St',
            'number' => 10,
            'postalCode' => '12345',
            'city' => 'Cityville',
            'region' => 'Stateville',
            'bus' => 0
        ]);

        // Seed Customer Addresses
        DB::table('customeraddress')->insert([
            'startDate' => now(),
            'endDate' => now()->addYears(1),
            'customerID' => $cstID,
            'addressID' => $addrID,
        ]);        

        // Seed Customer Contracts
        DB::table('customercontract')->insert([
            'customerID' => $cstID,
            'startDate' => now(),
            'endDate' => now()->addYears(1),
            'type' => 'Standard',
            'price' => 1000.00,
            'status' => 'Active',
        ]);
    }
}

