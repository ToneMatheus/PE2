<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run()
    {
        function generatePhone(){
            $phone = '047' . rand(0,9) . ' ' . sprintf("%03d", rand(0, 999)) . ' ' . sprintf("%03d", rand(0, 999));
            return $phone;
        }

        function generateDate(){
            $minBirthdate = Carbon::now()->subYears(18)->endOfDay();
            $maxBirthdate = Carbon::now()->endOfDay();

            $birthDate = Carbon::createFromTimestamp(rand($minBirthdate->timestamp, $maxBirthdate->timestamp));

            $birthDate = $birthDate->subYears(18);

            return $birthDate->toDateString();
        }

        DB::table('users')->insert([
            [   //Employee
                'id' => 1,
                'username' => 'bob',
                'first_name' => 'Bob',
                'last_name' => 'Doe',
                'password' => Hash::make('bob'),
                'address_id' => 1,
                'employee_profile_id' => 1,
                'is_company' => 0,
                'company_name' => null,
                'email' => 'bob@gmail',
                'phone_nbr' => generatePhone(),
                'birth_date' => generateDate(),
            ],
            [   //Employee
                'id' => 2,
                'username' => 'jef',
                'first_name' => 'jef',
                'last_name' => 'Doe',
                'password' => Hash::make('jef'),
                'address_id' => 2,
                'employee_profile_id' => 2,
                'is_company' => 0,
                'company_name' => null,
                'email' => 'jef@gmail',
                'phone_nbr' => generatePhone(),
                'birth_date' => generateDate(),
            ],
            [   //Employee who's Customer
                'id' => 3,
                'username' => 'kim',
                'first_name' => 'Kim',
                'last_name' => 'Doe',
                'password' => Hash::make('kim'),
                'address_id' => null,
                'employee_profile_id' => 3,
                'is_company' => 0,
                'company_name' => null,
                'email' => 'kim@gmail',
                'phone_nbr' => generatePhone(),
                'birth_date' => generateDate(),
            ],
            [   //Commercial Customer
                'id' => 4,
                'username' => 'emily',
                'first_name' => 'Emily',
                'last_name' => 'Doe',
                'password' => Hash::make('emily'),
                'address_id' => null,
                'employee_profile_id' => null,
                'is_company' => 1,
                'company_name' => 'ABC comp.',
                'email' => 'emily@gmail',
                'phone_nbr' => generatePhone(),
                'birth_date' => generateDate(),
            ],
            [   //Residential Customer
                'id' => 5,
                'username' => 'ann',
                'first_name' => 'Ann',
                'last_name' => 'Doe',
                'password' => Hash::make('ann'),
                'address_id' => null,
                'employee_profile_id' => null,
                'is_company' => 0,
                'company_name' => null,
                'email' => 'ann@gmail',
                'phone_nbr' => generatePhone(),
                'birth_date' => generateDate(),
            ],
        ]);
    }
}
