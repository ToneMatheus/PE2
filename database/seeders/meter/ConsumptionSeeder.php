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

            foreach($indexValues as $key => $indexValue){
                if ($key > 0) {
                    $prevIndexValue = $indexValues[$key - 1];
                    $consumptionValue = $indexValue->reading_value - $prevIndexValue->reading_value;
    
                    DB::table('consumptions')->insert([
                        'start_date' => $prevIndexValue->reading_date,
                        'end_date' => $indexValue->reading_date,
                        'consumption_value' => $consumptionValue,
                        'prev_index_id' => $prevIndexValue->id,
                        'current_index_id' => $indexValue->id,
                    ]);
                }
            }
        }
    }
}
