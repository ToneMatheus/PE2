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
            ['team_name' => 'Team blue'],
            ['team_name' => 'Team red'],
            ['team_name' => 'Team green'],
        ]);
    }
}
