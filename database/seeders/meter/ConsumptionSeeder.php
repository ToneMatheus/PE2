<?php
namespace Database\Seeders\Meter;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ConsumptionSeeder extends Seeder
{
    public function run()
    {
        DB::table('consumptions')->insert([
            [
                'start_date' => '2024-01-01',
                'end_date' => '2024-02-01',
                'consumption_value' => 35,
                'prev_index_id' => 1,
                'current_index_id' => 2 
            ],
            [
                'start_date' => '2024-02-01',
                'end_date' => '2024-03-01',
                'consumption_value' => 30,
                'prev_index_id' => 2,
                'current_index_id' => 3 
            ]
        ]);

        DB::table('consumptions')->insert([
            [
                'start_date' => '2024-01-01',
                'end_date' => '2024-02-01',
                'consumption_value' => 40,
                'prev_index_id' => 4,
                'current_index_id' => 5 
            ],
            [
                'start_date' => '2024-02-01',
                'end_date' => '2024-03-01',
                'consumption_value' => 120,
                'prev_index_id' => 5,
                'current_index_id' => 6
            ]
        ]);

        DB::table('consumptions')->insert([
            [
                'start_date' => '2024-01-01',
                'end_date' => '2024-02-01',
                'consumption_value' => 75,
                'prev_index_id' => 7,
                'current_index_id' => 8 
            ],
            [
                'start_date' => '2024-02-01',
                'end_date' => '2024-03-01',
                'consumption_value' => 30,
                'prev_index_id' => 8,
                'current_index_id' => 9
            ]
        ]);

        DB::table('consumptions')->insert([
            [
                'start_date' => '2024-01-01',
                'end_date' => '2024-02-01',
                'consumption_value' => 15,
                'prev_index_id' => 10,
                'current_index_id' => 11
            ],
            [
                'start_date' => '2024-02-01',
                'end_date' => '2024-03-01',
                'consumption_value' => 10,
                'prev_index_id' =>11,
                'current_index_id' => 12
            ]
        ]);
    }
}
