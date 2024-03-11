<?php

namespace Database\Seeders\Employee;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Holiday_type;

class HolidayTypeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('holiday_types')->insert([
            ['type' => 'Public Holiday'],
            ['type' => 'Annual Leave'],
            ['type' => 'Sick Leave'],
        ]);
    }
}
