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
                'employee_profile_id' => null,
                'is_company' => 0,
                'company_name' => null,
                'email' => 'ann@gmail',
                'phone_nbr' => generatePhone(),
                'birth_date' => generateDate(),
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
                'email' => 'marie@gmail',
                'phone_nbr' => generatePhone(),
                'birth_date' => generateDate(),
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
                'email' => 'mark@gmail',
                'phone_nbr' => generatePhone(),
                'birth_date' => generateDate(),
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
                'email' => 'rob@gmail',
                'phone_nbr' => generatePhone(),
                'birth_date' => generateDate(),
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
                'email' => 'jan@gmail',
                'phone_nbr' => generatePhone(),
                'birth_date' => generateDate(),
            ],
            [   
                'id' => 10,
                'username' => 'joseph',
                'first_name' => 'Joseph',
                'last_name' => 'Doe',
                'password' => Hash::make('joseph'),
                'employee_profile_id' => 4,
                'is_company' => 0,
                'company_name' => null,
                'email' => 'joseph@gmail',
                'phone_nbr' => generatePhone(),
                'birth_date' => generateDate(),
            ],
            [   
                'id' => 11,
                'username' => 'jimmy',
                'first_name' => 'Jimmy',
                'last_name' => 'Doe',
                'password' => Hash::make('jimmy'),
                'employee_profile_id' => 5,
                'is_company' => 0,
                'company_name' => null,
                'email' => 'jimmy@gmail',
                'phone_nbr' => generatePhone(),
                'birth_date' => generateDate(),
            ],
            [   
                'id' => 12,
                'username' => 'james',
                'first_name' => 'James',
                'last_name' => 'Doe',
                'password' => Hash::make('james'),
                'employee_profile_id' => 6,
                'is_company' => 0,
                'company_name' => null,
                'email' => 'james@gmail',
                'phone_nbr' => generatePhone(),
                'birth_date' => generateDate(),
            ],
            [   
                'id' => 13,
                'username' => 'jane',
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'password' => Hash::make('jane'),
                'employee_profile_id' => 7,
                'is_company' => 0,
                'company_name' => null,
                'email' => 'jane@gmail',
                'phone_nbr' => generatePhone(),
                'birth_date' => generateDate(),
            ],
            [   
                'id' => 14,
                'username' => 'pearl',
                'first_name' => 'Pearl',
                'last_name' => 'Doe',
                'password' => Hash::make('pearl'),
                'employee_profile_id' => 8,
                'is_company' => 0,
                'company_name' => null,
                'email' => 'pearl@gmail',
                'phone_nbr' => generatePhone(),
                'birth_date' => generateDate(),
            ],
            [   
                'id' => 15,
                'username' => 'rose',
                'first_name' => 'Rose',
                'last_name' => 'Doe',
                'password' => Hash::make('rose'),
                'employee_profile_id' => 9,
                'is_company' => 0,
                'company_name' => null,
                'email' => 'rose@gmail',
                'phone_nbr' => generatePhone(),
                'birth_date' => generateDate(),
            ],
        ]);
    }
}
