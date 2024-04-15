<?php

namespace Database\Seeders\Employee;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Team;

class TeamsSeeder extends Seeder
{
    public function run()
    {
        DB::table('teams')->insert([
            ['team_name' => 'HR'],
            ['team_name' => 'Invoice'],
            ['team_name' => 'Meters'],
            ['team_name' => 'Customer service']
        ]);
    }
}
