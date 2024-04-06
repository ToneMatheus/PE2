<?php

namespace Database\Seeders\Employee;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\TeamMember;

class TeamMembersSeeder extends Seeder
{
    public function run()
    {
        DB::table('team_members')->insert([
        [
            'user_id' => 1,
            'team_id' => 1,
            'is_manager' => 1,
        ],
        [
            'user_id' => 2,
            'team_id' => 1,
            'is_manager' => 0,
        ],
        [
            'user_id' => 3,
            'team_id' => 1,
            'is_manager' => 0,
        ],
        [
            'user_id' => 4,
            'team_id' => 1,
            'is_manager' => 0,
        ],
        [
            'user_id' => 5,
            'team_id' => 2,
            'is_manager' => 1,
        ],
        [
            'user_id' => 6,
            'team_id' => 2,
            'is_manager' => 0,
        ],
        [
            'user_id' => 7,
            'team_id' => 2,
            'is_manager' => 0,
        ],
        [
            'user_id' => 8,
            'team_id' => 2,
            'is_manager' => 0,
        ],
        [
            'user_id' => 9,
            'team_id' => 3,
            'is_manager' => 1,
        ],
        [
            'user_id' => 10,
            'team_id' => 3,
            'is_manager' => 0,
        ],
        [
            'user_id' => 11,
            'team_id' => 3,
            'is_manager' => 0,
        ],
        [
            'user_id' => 12,
            'team_id' => 3,
            'is_manager' => 0,
        ],
        [
            'user_id' => 13,
            'team_id' => 4,
            'is_manager' => 1,
        ],
        [
            'user_id' => 14,
            'team_id' => 4,
            'is_manager' => 0,
        ],
        [
            'user_id' => 15,
            'team_id' => 4,
            'is_manager' => 0,
        ],
    ]);
    }
}
