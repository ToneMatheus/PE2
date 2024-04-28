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
                'employee_profile_id' => 1,
                'is_company' => 0,
                'company_name' => null,
                'email' => 'bob@gmail.com',
                'phone_nbr' => generatePhone(),
                'birth_date' => generateDate(),
                'nationality' => 'Belgian',
                'changed_default' => 1
            ],
            [   //Employee
                'id' => 2,
                'username' => 'jef',
                'first_name' => 'jef',
                'last_name' => 'Doe',
                'password' => Hash::make('jef'),
                'employee_profile_id' => 2,
                'is_company' => 0,
                'company_name' => null,
                'email' => 'jef@gmail.com',
                'phone_nbr' => generatePhone(),
                'birth_date' => generateDate(),
                'nationality' => 'Belgian',
                'changed_default' => 1
            ],
            [   //Employee who's Customer
                'id' => 3,
                'username' => 'kim',
                'first_name' => 'Kim',
                'last_name' => 'Doe',
                'password' => Hash::make('kim'),
                'employee_profile_id' => 3,
                'is_company' => 0,
                'company_name' => null,
                'email' => 'kim@gmail.com',
                'phone_nbr' => generatePhone(),
                'birth_date' => generateDate(),
                'nationality' => 'Belgian',
                'changed_default' => 1
            ],
            [   //Commercial Customer
                'id' => 4,
                'username' => 'emily',
                'first_name' => 'Emily',
                'last_name' => 'Doe',
                'password' => Hash::make('emily'),
                'employee_profile_id' => null,
                'is_company' => 1,
                'company_name' => 'ABC comp.',
                'email' => 'emily@gmail.com',
                'phone_nbr' => generatePhone(),
                'birth_date' => generateDate(),
                'nationality' => 'Belgian',
                'changed_default' => 1
            ],
            [   //Residential Customer
                'id' => 5,
                'username' => 'ann',
                'first_name' => 'Ann',
                'last_name' => 'Doe',
                'password' => Hash::make('ann'),
                'employee_profile_id' => null,
                'is_company' => 0,
                'company_name' => null,
                'email' => 'ann@gmail.com',
                'phone_nbr' => generatePhone(),
                'birth_date' => generateDate(),
                'nationality' => 'Belgian',
                'changed_default' => 1
            ],
            [   
                'id' => 6,
                'username' => 'marie',
                'first_name' => 'Marie',
                'last_name' => 'Doe',
                'password' => Hash::make('marie'),
                'employee_profile_id' => null,
                'is_company' => 0,
                'company_name' => null,
                'email' => 'marie@gmail.com',
                'phone_nbr' => generatePhone(),
                'birth_date' => generateDate(),
                'nationality' => 'Belgian',
                'changed_default' => 1
            ],
            [   
                'id' => 7,
                'username' => 'mark',
                'first_name' => 'Mark',
                'last_name' => 'Doe',
                'password' => Hash::make('mark'),
                'employee_profile_id' => null,
                'is_company' => 0,
                'company_name' => null,
                'email' => 'mark@gmail.com',
                'phone_nbr' => generatePhone(),
                'birth_date' => generateDate(),
                'nationality' => 'Belgian',
                'changed_default' => 1
            ],
            [   
                'id' => 8,
                'username' => 'rob',
                'first_name' => 'Rob',
                'last_name' => 'Doe',
                'password' => Hash::make('rob'),
                'employee_profile_id' => null,
                'is_company' => 0,
                'company_name' => null,
                'email' => 'rob@gmail.com',
                'phone_nbr' => generatePhone(),
                'birth_date' => generateDate(),
                'nationality' => 'Belgian',
                'changed_default' => 1
            ],
            [   
                'id' => 9,
                'username' => 'jan',
                'first_name' => 'Jan',
                'last_name' => 'Doe',
                'password' => Hash::make('jan'),
                'employee_profile_id' => null,
                'is_company' => 0,
                'company_name' => null,
                'email' => 'jan@gmail.com',
                'phone_nbr' => generatePhone(),
                'birth_date' => generateDate(),
                'nationality' => 'Belgian',
                'changed_default' => 1
            ],
            
        ]);
    }
}
