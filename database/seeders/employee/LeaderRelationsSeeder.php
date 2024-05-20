<?php

namespace Database\Seeders\Employee;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\LeaderRelations;


class LeaderRelationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('leader_relations')->insert([
            [
                'leader_id' => 5,
                'employee_id' => 1,
                'relation' => 'manager'
            ],
            [
                'leader_id' => 5,
                'employee_id' => 2,
                'relation' => 'manager'
            ],
            [
                'leader_id' => 5,
                'employee_id' => 3,
                'relation' => 'manager'
            ],
            [
                'leader_id' => 4,
                'employee_id' => 4,
                'relation' => 'manager'
            ],
            [
                'leader_id' => 4,
                'employee_id' => 5,
                'relation' => 'manager'
            ],
            [
                'leader_id' => 4,
                'employee_id' => 6,
                'relation' => 'manager'
            ],

        ]);
    }
}
