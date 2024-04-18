<?php

namespace Database\Seeders\Meter;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class IndexValueSeeder extends Seeder
{
    public function run()
    {
        function generateIndexValues($startDate, $rangeMin, $rangeMax, $meterID, &$id) {
            $currentDate = $startDate->copy(); // Ensure original start date is not modified
            $endDate = Carbon::now(); // Current date
        
            while ($currentDate->lte($endDate)) {
                DB::table('index_values')->insert([
                    'reading_date' => $currentDate->format('Y-m-d'),
                    'reading_value' => rand($rangeMin, $rangeMax),
                    'meter_id' => $meterID,
                ]);

                $currentDate->addMonth();
                $id++;
            }
        }
        
        $startYear = 2024;
        $startDate = Carbon::create($startYear, 1, 1);
        $id = 1;
        
        $meterRanges = [
            ['id' => 1, 'rangeMin' => 300, 'rangeMax' => 500],
            ['id' => 2, 'rangeMin' => 1600, 'rangeMax' =>  2000],
            ['id' => 3, 'rangeMin' => 200, 'rangeMax' => 400],
            ['id' => 4, 'rangeMin' => 400, 'rangeMax' => 500],
        ];
        
        foreach ($meterRanges as $meter) {
            $meterID = $meter['id'];
            $rangeMin = $meter['rangeMin'];
            $rangeMax = $meter['rangeMax'];
            $tempId = $id; // Store the starting id for this meter
            generateIndexValues($startDate, $rangeMin, $rangeMax, $meterID, $tempId);
            $id = $tempId; // Update the main id counter
        }
    }
}
