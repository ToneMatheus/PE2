<?php
namespace Database\Seeders\Meter;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ConsumptionSeeder extends Seeder
{
    public function run()
    {
        for($i = 1; $i <= 4; $i++){
            DB::table('consumptions')->insert([
                'start_date' => '2022-01-01',
                'end_date' => '2023-01-01',
                'consumption_value' => 0,
                'prev_index_id' => null,
                'current_index_id' => $i
            ]);
        }
        
        for($i = 1; $i <= 4; $i++){
            $indexValues = DB::table('index_values')->where('meter_id', '=', $i)->get();

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
}