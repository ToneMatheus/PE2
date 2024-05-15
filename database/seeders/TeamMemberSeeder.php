<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Team_Member;
use Illuminate\Support\Facades\DB;

class TeamMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('team_members')->insert([
            [
                'user_id' => 1,
                'team_id' => 1, // assuming team_id is 1
                'is_manager' => 1, // 1 for manager
                'is_active' => 1, // assuming the member is active
            ],
            [
                'user_id' => 4,
                'team_id' => 1, // assuming team_id is 1
                'is_manager' => 0, // 0 for normal team member
                'is_active' => 1, // assuming the member is active
            ]
        ]);
    }
}