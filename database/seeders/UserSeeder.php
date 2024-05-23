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

        function generateIBAN() {
            $iban = "BE";
            $num = rand(1000000, 9999999);
            $iban .= strval($num);
            $num = rand(1000000, 9999999);
            $iban .= strval($num);
            return $iban;
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
                'changed_default' => 1,
                'IBAN' => generateIBAN(),
                'is_active' => 1,
                'is_landlord' => 0,
                'index_method' => 'website'
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
                'changed_default' => 1,
                'IBAN' => generateIBAN(),
                'is_active' => 1,
                'is_landlord' => 0,
                'index_method' => 'website'
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
                'changed_default' => 1,
                'IBAN' => generateIBAN(),
                'is_active' => 1,
                'is_landlord' => 0,
                'index_method' => 'website'
            ],
            [   //Commercial Customer
                'id' => 4,
                'username' => 'emily',
                'first_name' => 'Emily',
                'last_name' => 'Doe',
                'password' => Hash::make('emily'),
                'employee_profile_id' => 4,
                'is_company' => 1,
                'company_name' => 'ABC comp.',
                'email' => 'emily@gmail.com',
                'phone_nbr' => generatePhone(),
                'birth_date' => generateDate(),
                'nationality' => 'Belgian',
                'changed_default' => 1,
                'IBAN' => generateIBAN(),
                'is_active' => 1,
                'is_landlord' => 0,
                'index_method' => 'website'
            ],
            [   //Employee who is customer service line 1
                'id' => 5,
                'username' => 'ann',
                'first_name' => 'Ann',
                'last_name' => 'Doe',
                'password' => Hash::make('ann'),
                'employee_profile_id' => 5,
                'is_company' => 0,
                'company_name' => null,
                'email' => 'ann@gmail.com',
                'phone_nbr' => generatePhone(),
                'birth_date' => generateDate(),
                'nationality' => 'Belgian',
                'changed_default' => 1,
                'IBAN' => generateIBAN(),
                'is_active' => 1,
                'is_landlord' => 0,
                'index_method' => 'website'
            ],

            [   //Employee who is customer service line 2

                'id' => 6,
                'username' => 'marie',
                'first_name' => 'Marie',
                'last_name' => 'Doe',
                'password' => Hash::make('marie'),
                'employee_profile_id' => 6,
                'is_company' => 0,
                'company_name' => null,
                'email' => 'marie@gmail.com',
                'phone_nbr' => generatePhone(),
                'birth_date' => generateDate(),
                'nationality' => 'Belgian',
                'changed_default' => 1,
                'IBAN' => generateIBAN(),
                'is_active' => 1,
                'is_landlord' => 0,
                'index_method' => 'website'
            ],

            [   //Employee who is customer service line 3

                'id' => 7,
                'username' => 'mark',
                'first_name' => 'Mark',
                'last_name' => 'Doe',
                'password' => Hash::make('mark'),
                'employee_profile_id' => 7,
                'is_company' => 0,
                'company_name' => null,
                'email' => 'mark@gmail.com',
                'phone_nbr' => generatePhone(),
                'birth_date' => generateDate(),
                'nationality' => 'Belgian',
                'changed_default' => 1,
                'IBAN' => generateIBAN(),
                'is_active' => 1,
                'is_landlord' => 0,
                'index_method' => 'website'
            ],
            [
                'id' => 8,
                'username' => 'rob',
                'first_name' => 'Rob',
                'last_name' => 'Doe',
                'password' => Hash::make('rob'),
                'employee_profile_id' => 8,
                'is_company' => 0,
                'company_name' => null,
                'email' => 'rob@gmail.com',
                'phone_nbr' => generatePhone(),
                'birth_date' => generateDate(),
                'nationality' => 'Belgian',
                'changed_default' => 1,
                'IBAN' => generateIBAN(),
                'is_active' => 1,
                'is_landlord' => 0,
                'index_method' => 'website'
            ],
            [
                'id' => 9,
                'username' => 'jan',
                'first_name' => 'Jan',
                'last_name' => 'Doe',
                'password' => Hash::make('jan'),
                'employee_profile_id' => 9,
                'is_company' => 0,
                'company_name' => null,
                'email' => 'jan@gmail.com',
                'phone_nbr' => generatePhone(),
                'birth_date' => generateDate(),
                'nationality' => 'Belgian',
                'changed_default' => 1,
                'IBAN' => generateIBAN(),
                'is_active' => 1,
                'is_landlord' => 0,
                'index_method' => 'website'
            ],
            [
                'id' => 10,
                'username' => 'joseph',
                'first_name' => 'Joseph',
                'last_name' => 'Doe',
                'password' => Hash::make('joseph'),
                'employee_profile_id' => 10,
                'is_company' => 0,
                'company_name' => null,
                'email' => 'joseph@gmail',
                'phone_nbr' => generatePhone(),
                'birth_date' => generateDate(),
                'nationality' => 'Belgian',
                'changed_default' => 1,
                'IBAN' => generateIBAN(),
                'is_active' => 1,
                'is_landlord' => 1,
                'index_method' => 'website'
            ],
            [
                'id' => 11,
                'username' => 'jimmy',
                'first_name' => 'Jimmy',
                'last_name' => 'Doe',
                'password' => Hash::make('jimmy'),
                'employee_profile_id' => 11,
                'is_company' => 0,
                'company_name' => null,
                'email' => 'jimmy@gmail',
                'phone_nbr' => generatePhone(),
                'birth_date' => generateDate(),
                'nationality' => 'Belgian',
                'changed_default' => 1,
                'IBAN' => generateIBAN(),
                'is_active' => 1,
                'is_landlord' => 0,
                'index_method' => 'website'
            ],
            [
                'id' => 12,
                'username' => 'james',
                'first_name' => 'James',
                'last_name' => 'Doe',
                'password' => Hash::make('james'),
                'employee_profile_id' => 12,
                'is_company' => 0,
                'company_name' => null,
                'email' => 'james@gmail',
                'phone_nbr' => generatePhone(),
                'birth_date' => generateDate(),
                'nationality' => 'Belgian',
                'changed_default' => 1,
                'IBAN' => generateIBAN(),
                'is_active' => 1,
                'is_landlord' => 0,
                'index_method' => 'website'
            ],
            [
                'id' => 13,
                'username' => 'jeraldine',
                'first_name' => 'Jeraldine',
                'last_name' => 'Doe',
                'password' => Hash::make('jeraldine'),
                'employee_profile_id' => 13,
                'is_company' => 0,
                'company_name' => null,
                'email' => 'jane@gmail',
                'phone_nbr' => generatePhone(),
                'birth_date' => generateDate(),
                'nationality' => 'Belgian',
                'changed_default' => 1,
                'IBAN' => generateIBAN(),
                'is_active' => 1,
                'is_landlord' => 0,
                'index_method' => 'website'
            ],
            [
                'id' => 14,
                'username' => 'pearl',
                'first_name' => 'Pearl',
                'last_name' => 'Doe',
                'password' => Hash::make('pearl'),
                'employee_profile_id' => 14,
                'is_company' => 0,
                'company_name' => null,
                'email' => 'pearl@gmail',
                'phone_nbr' => generatePhone(),
                'birth_date' => generateDate(),
                'nationality' => 'Belgian',
                'changed_default' => 1,
                'IBAN' => generateIBAN(),
                'is_active' => 1,
                'is_landlord' => 0,
                'index_method' => 'website'
            ],
            [
                'id' => 15,
                'username' => 'rose',
                'first_name' => 'Rose',
                'last_name' => 'Doe',
                'password' => Hash::make('rose'),
                'employee_profile_id' => 15,
                'is_company' => 0,
                'company_name' => null,
                'email' => 'rose@gmail',
                'phone_nbr' => generatePhone(),
                'birth_date' => generateDate(),
                'nationality' => 'Belgian',
                'changed_default' => 1,
                'IBAN' => generateIBAN(),
                'is_active' => 1,
                'is_landlord' => 0,
                'index_method' => 'paper'
            ],
            [
                'id' => 16,
                'username' => 'carry',
                'first_name' => 'Carry',
                'last_name' => 'Doe',
                'password' => Hash::make('carry'),
                'employee_profile_id' => null,
                'is_company' => 0,
                'company_name' => null,
                'email' => 'carry@gmail',
                'phone_nbr' => generatePhone(),
                'birth_date' => generateDate(),
                'nationality' => 'Belgian',
                'changed_default' => 1,
                'IBAN' => generateIBAN(),
                'is_active' => 1,
                'is_landlord' => 0,
                'index_method' => 'paper'
            ],
            [
                'id' => 17,
                'username' => 'homer',
                'first_name' => 'Homer',
                'last_name' => 'Doe',
                'password' => Hash::make('homer'),
                'employee_profile_id' => null,
                'is_company' => 0,
                'company_name' => null,
                'email' => 'homer@gmail',
                'phone_nbr' => generatePhone(),
                'birth_date' => generateDate(),
                'nationality' => 'Belgian',
                'changed_default' => 1,
                'IBAN' => generateIBAN(),
                'is_active' => 1,
                'is_landlord' => 0,
                'index_method' => 'website'
            ],
            [
                'id' => 18,
                'username' => 'linda',
                'first_name' => 'Linda',
                'last_name' => 'Doe',
                'password' => Hash::make('linda'),
                'employee_profile_id' => null,
                'is_company' => 0,
                'company_name' => null,
                'email' => 'linda@gmail',
                'phone_nbr' => generatePhone(),
                'birth_date' => generateDate(),
                'nationality' => 'Belgian',
                'changed_default' => 1,
                'IBAN' => generateIBAN(),
                'is_active' => 1,
                'is_landlord' => 0,
                'index_method' => 'website'
            ],
            [
                'id' => 19,
                'username' => 'lesly',
                'first_name' => 'Lesly',
                'last_name' => 'Doe',
                'password' => Hash::make('lesly'),
                'employee_profile_id' => null,
                'is_company' => 0,
                'company_name' => null,
                'email' => 'lesly@gmail',
                'phone_nbr' => generatePhone(),
                'birth_date' => generateDate(),
                'nationality' => 'Belgian',
                'changed_default' => 1,
                'IBAN' => generateIBAN(),
                'is_active' => 0,
                'is_landlord' => 0,
                'index_method' => 'website'
            ],
            [
                'id' => 20,
                'username' => 'louise',
                'first_name' => 'Louise',
                'last_name' => 'Doe',
                'password' => Hash::make('louise'),
                'employee_profile_id' => null,
                'is_company' => 0,
                'company_name' => null,
                'email' => 'louise@gmail',
                'phone_nbr' => generatePhone(),
                'birth_date' => generateDate(),
                'nationality' => 'Belgian',
                'changed_default' => 1,
                'IBAN' => generateIBAN(),
                'is_active' => 0,
                'is_landlord' => 0,
                'index_method' => 'website'
            ],
            
            [
                'id' => 1000,
                'username' => 'undefined',
                'first_name' => 'Not Defined',
                'last_name' => '',
                'password' => Hash::make('undefined'),
                'employee_profile_id' => 1000,
                'is_company' => 0,
                'company_name' => null,
                'email' => 'undefined@gmail.com',
                'phone_nbr' => generatePhone(),
                'birth_date' => generateDate(),
                'nationality' => 'Dutch',
                'changed_default' => 1,
                'IBAN' => generateIBAN(),
                'is_active' => 1,
                'is_landlord' => 0,
                'index_method' => 'website'
            ],
        ]);
    }
}
