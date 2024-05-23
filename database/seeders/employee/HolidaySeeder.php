<?php

namespace Database\Seeders\Employee;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Holiday;

class HolidaySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('holidays')->insert([
            [
                'employee_profile_id' => 1,
                'request_date' => '2023-12-12',
                'start_date' => '2024-01-01',
                'end_date' => '2024-01-02',
                'holiday_type_id' => 1,
                'reason' => 'New Year',
                'fileLocation' => null,
                'manager_approval' => 1,
                'boss_approval' => 1,
                'is_active' => 0,
            ],/*
            [
                'employee_profile_id' => 2,
                'request_date' => '2023-11-11',
                'start_date' => '2024-02-15',
                'end_date' => '2024-02-16',
                'holiday_type_id' => 2,
                'reason' => 'Annual Leave',
                'fileLocation' => null,
                'manager_approval' => 1,
                'boss_approval' => 1,
                'is_active' => 0,
            ],*/
        ]);
    }
}
