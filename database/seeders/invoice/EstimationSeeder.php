<?php
namespace Database\Seeders\Invoice;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstimationSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('estimations')->insert([
        [
            'nbr_occupants' => 20,
        'is_home_all_day' => 1,
        'heat_with_power' => 1,
        'water_with_power' => 1,
        'cook_with_power' => 1,
        'nbr_air_con' => 1,
        'nbr_fridges' => 1,
        'nbr_washers' => 1,
        'nbr_computers' => 1,
        'nbr_entertainment' => 1,
        'nbr_dishwashers' => 1,
        'estimation_total' => 3300,
        'meter_id' => 1
        ],
            [
                'nbr_occupants' => 20,
            'is_home_all_day' => 1,
            'heat_with_power' => 1,
            'water_with_power' => 1,
            'cook_with_power' => 1,
            'nbr_air_con' => 1,
            'nbr_fridges' => 1,
            'nbr_washers' => 1,
            'nbr_computers' => 1,
            'nbr_entertainment' => 1,
            'nbr_dishwashers' => 1,
            'estimation_total' => 6600,
            'meter_id' => 2
            ],
            [
                'nbr_occupants' => 2,
            'is_home_all_day' => 1,
            'heat_with_power' => 1,
            'water_with_power' => 1,
            'cook_with_power' => 1,
            'nbr_air_con' => 1,
            'nbr_fridges' => 1,
            'nbr_washers' => 1,
            'nbr_computers' => 1,
            'nbr_entertainment' => 1,
            'nbr_dishwashers' => 1,
            'estimation_total' => 3300,
            'meter_id' => 3
            ],
            [
                'nbr_occupants' => 2,
            'is_home_all_day' => 1,
            'heat_with_power' => 1,
            'water_with_power' => 1,
            'cook_with_power' => 1,
            'nbr_air_con' => 1,
            'nbr_fridges' => 1,
            'nbr_washers' => 1,
            'nbr_computers' => 1,
            'nbr_entertainment' => 1,
            'nbr_dishwashers' => 1,
            'estimation_total' => 3400,
            'meter_id' => 4
            ],
            [
                'nbr_occupants' => 2,
            'is_home_all_day' => 1,
            'heat_with_power' => 1,
            'water_with_power' => 1,
            'cook_with_power' => 1,
            'nbr_air_con' => 1,
            'nbr_fridges' => 1,
            'nbr_washers' => 1,
            'nbr_computers' => 1,
            'nbr_entertainment' => 1,
            'nbr_dishwashers' => 1,
            'estimation_total' => 3400,
            'meter_id' => 5
            ],
            [
                'nbr_occupants' => 2,
            'is_home_all_day' => 1,
            'heat_with_power' => 1,
            'water_with_power' => 1,
            'cook_with_power' => 1,
            'nbr_air_con' => 1,
            'nbr_fridges' => 1,
            'nbr_washers' => 1,
            'nbr_computers' => 1,
            'nbr_entertainment' => 1,
            'nbr_dishwashers' => 1,
            'estimation_total' => 3400,
            'meter_id' => 6
            ],
            [
                'nbr_occupants' => 2,
            'is_home_all_day' => 1,
            'heat_with_power' => 1,
            'water_with_power' => 1,
            'cook_with_power' => 1,
            'nbr_air_con' => 1,
            'nbr_fridges' => 1,
            'nbr_washers' => 1,
            'nbr_computers' => 1,
            'nbr_entertainment' => 1,
            'nbr_dishwashers' => 1,
            'estimation_total' => 3200,
            'meter_id' => 7
            ]
        ]);
    }
}