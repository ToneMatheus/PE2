<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Encrypt passwords using Hash::make
        $encryptedBobPassword = Hash::make('Bob');
        $encryptedJefPassword = Hash::make('Jef');
        $encryptedKimPassword = Hash::make('Kim');

        // Insert multiple records into the users table
        DB::table('user')->insert([
            [
                'ID' => 1,
                'username' => 'Bob',
                'password' => $encryptedBobPassword,
                'email' => 'bob@gmail.com',
            ],
            [
                'ID' => 2,
                'username' => 'Jef',
                'password' => $encryptedJefPassword,
                'email' => 'jef@gmail.com',
            ],
            [
                'ID' => 3,
                'username' => 'Kim',
                'password' => $encryptedKimPassword,
                'email' => 'kim@gmail.com',
            ]
        ]);
    }
}
