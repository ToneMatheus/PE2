<?php
namespace Database\Seeders\Invoice;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstimationSeeder extends Seeder
{
    public function run(): void
    {
        function calculateEstimationTotal($nrOccupants, $isHomeAllDay, $heatWithPower, $waterWithPower, $cookWithPower, $nrAirCon, $nrFridges, $nrWashers, $nrComputers, $nrEntertainment, $nrDishwashers) {
            $occupantFactor = 50;
            $homeAllDayFactor = 50;
            $heatFactor = 50;
            $waterFactor = 50;
            $cookFactor = 20; 
            $airConFactor = 10;
            $fridgeFactor = 15;
            $washerFactor = 10;
            $computerFactor = 10; 
            $entertainmentFactor = 10; 
            $dishwasherFactor = 10; 
    
            
            $totalConsumption = ($nrOccupants * $occupantFactor) + 
                                ($isHomeAllDay * $homeAllDayFactor) + 
                                ($heatWithPower * $heatFactor) + 
                                ($waterWithPower * $waterFactor) + 
                                ($cookWithPower * $cookFactor) + 
                                ($nrAirCon * $airConFactor) + 
                                ($nrFridges * $fridgeFactor) + 
                                ($nrWashers * $washerFactor) + 
                                ($nrComputers * $computerFactor) + 
                                ($nrEntertainment * $entertainmentFactor) + 
                                ($nrDishwashers * $dishwasherFactor);
    
            return $totalConsumption;
        }
        
        $estimations = [];

        for ($i = 1; $i <= 4; $i++) {
            if ($i == 2) {
                $nrOccupants = 20;
                $isHomeAllDay = 1;
                $heatWithPower = 1;
                $waterWithPower = 1;
                $cookWithPower = 0;
                $nrAirCon = 5;
                $nrFridges = 3;
                $nrWashers = 3;
                $nrComputers = 20;
                $nrEntertainment = 5;
                $nrDishwashers = 3;
            } else {
                $nrOccupants = rand(1, 5);
                $isHomeAllDay = rand(0, 1);
                $heatWithPower = rand(0, 1);
                $waterWithPower = rand(0, 1);
                $cookWithPower = rand(0, 1);
                $nrAirCon = rand(1, 3);
                $nrFridges = rand(1, 3);
                $nrWashers = rand(1, 3);
                $nrComputers = rand(1, 5);
                $nrEntertainment = rand(1, 7);
                $nrDishwashers = rand(1, 2);
            }

            // Calculate the total estimated consumption in kWh
            $estimationTotal = calculateEstimationTotal(
                $nrOccupants, 
                $isHomeAllDay, 
                $heatWithPower, 
                $waterWithPower, 
                $cookWithPower, 
                $nrAirCon, 
                $nrFridges, 
                $nrWashers, 
                $nrComputers, 
                $nrEntertainment, 
                $nrDishwashers
            );

            $estimations[] = [
                'id' => $i,
                'nbr_occupants' => $nrOccupants,
                'is_home_all_day' => $isHomeAllDay,
                'heat_with_power' => $heatWithPower,
                'water_with_power' => $waterWithPower,
                'cook_with_power' => $cookWithPower,
                'nbr_air_con' => $nrAirCon,
                'nbr_fridges' => $nrFridges,
                'nbr_washers' => $nrWashers,
                'nbr_computers' => $nrComputers,
                'nbr_entertainment' => $nrEntertainment,
                'nbr_dishwashers' => $nrDishwashers,
                'estimation_total' => $estimationTotal,
                'meter_id' => $i,
            ];
        }
        
        DB::table('estimations')->insert($estimations);
    }
}