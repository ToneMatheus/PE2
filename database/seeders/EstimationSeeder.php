<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstimationSeeder extends Seeder
{
    public function run(): void
    {
        $estimations = [];

        for ($i = 1; $i <= 4; $i++){

            $estimations[] = [
                'ID' => $i,
                'nrOccupants' => $nrOccupants = rand(1,5),
                'isHomeAllDay' => $isHomeAllDay = rand(0,1),
                'heatWithPower' => $heatWithPower = rand(0,1),
                'waterWithPower' => $waterWithPower = rand(0,1),
                'cookWithPower' => $cookWithPower = rand(0,1),
                'nrAirCon' => $nrAirCon = rand(1,3),
                'nrFridges' => $nrFridges = rand(1,3),
                'nrWashers' => $nrWashers = rand(1,3),
                'nrComputers' => $nrComputers = rand(1,5),
                'nrEntertainment' => $nrEntertainment = rand(1,7),
                'nrDishwashers' => $nrDishwashers = rand(1,2),

                'estimationTotal' => $nrOccupants + $isHomeAllDay + $heatWithPower + $waterWithPower + 
                $cookWithPower + $nrAirCon + $nrFridges + $nrWashers + $nrComputers + $nrEntertainment + 
                $nrDishwashers,

                'meterID' => $i,
            ];
            
        }
        DB::table('estimation')->insert($estimations);
    }
}
