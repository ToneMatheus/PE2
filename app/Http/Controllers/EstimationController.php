<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Estimation;
use App\Models\Meter;
use App\Models\Consumption;
use Carbon\Carbon;

class EstimationController extends Controller
{
    // TEMP
    public function showButton()
    {
        return view('Invoices.testingEstimation');
    }
    // Guest Public functions
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function showGuestEnergyEstimate(Request $request)
    {
        $totalEstimateYear = $this->calculateGuestTotalEnergyEstimate($request);
        return view('Invoices.EstimationGuest', ['totalEstimateYear' => $totalEstimateYear]);
    }
    /**
     * @return \Illuminate\View\View
     */
    public function showGuestForm()
    {
        return view('Invoices.EstimationGuest');
    }
    /**
     * @return void
     */
    /**
     * @param int $meterID
     * @return void
     */
    public static function UpdateEstimation(int $meterID): void
    {
        $now = Carbon::now();
        $month = $now->format('m');
        $year = $now->format('Y');
        $meterReadings = DB::table('index_values')
            ->where(function ($query) use ($year) {
                $query->whereYear('reading_date', $year)
                    ->orWhereYear('reading_date', $year - 1);
            })
            ->where('meter_id', '=', $meterID)
            ->select('reading_value')
            ->get()->toArray();

        if (sizeof($meterReadings) == 2) {
            DB::table('estimations')->where('meter_id', $meterID)->update(array('estimation_total' => $meterReadings[1]->reading_value));
        }
        else if (sizeof($meterReadings) == 1) {
            DB::table('estimations')->where('meter_id', $meterID)->update(array('estimation_total' => $meterReadings[0]->reading_value));
        }
        else {
            EstimationController::calculateMeterEnergyEstimate($meterID);
        }
    }
    // Guest Private functions
    /**
     * @param \Illuminate\Http\Request $request
     * @return int
     */
    private function calculateGuestTotalEnergyEstimate(Request $request): int
    {
        $request->validate([
            'nrOccupants' => 'required|integer|min:1|max:15',
            'nrAirCon' => 'required|integer|min:0|max:10',
            'nrFridges' => 'required|integer|min:0|max:10',
            'nrWashers' => 'required|integer|min:0|max:10',
            'nrComputers' => 'required|integer|min:0|max:15',
            'nrEntertainment' => 'required|integer|min:0|max:15',
            'nrDishwashers' => 'required|integer|min:0|max:10',
        ], [
            'nrOccupants.min' => 'This field must be at least 1.','nrOccupants.max' => 'This field cannot be more than 15.',
            'nrAirCon.min' => 'This field must be at least 0.','nrAirCon.max' => 'This field cannot be more than 10.',
            'nrFridges.min' => 'This field must be at least 0.','nrFridges.max' => 'This field cannot be more than 10.',
            'nrWashers.min' => 'This field must be at least 0.','nrWashers.max' => 'This field cannot be more than 10.',
            'nrComputers.min' => 'This field must be at least 0.','nrComputers.max' => 'This field cannot be more than 15.',
            'nrEntertainment.min' => 'This field must be at least 0.','nrEntertainment.max' => 'This field cannot be more than 15.',
            'nrDishwashers.min' => 'This field must be at least 0.','nrDishwashers.max' => 'This field cannot be more than 10.',
        ]);
        $nrOccupants = $request->input('nrOccupants');
        $isHomeAllDay = $request->input('isHomeAllDay');
        $heatWithPower = $request->input('heatWithPower');
        $waterWithPower = $request->input('waterWithPower');
        $cookWithPower = $request->input('cookWithPower');
        $nrAirCon = $request->input('nrAirCon');
        $nrFridges = $request->input('nrFridges');
        $nrWashers = $request->input('nrWashers');
        $nrComputers = $request->input('nrComputers');
        $nrEntertainment = $request->input('nrEntertainment');
        $nrDishwashers = $request->input('nrDishwashers');
        // Lowered
        $estimateLights = 324;
        $estimateHeat = ($heatWithPower * $isHomeAllDay * 10000) +
                        ($heatWithPower * (1 - $isHomeAllDay) * 5000) +
                        ((1 - $heatWithPower) * $isHomeAllDay * 500) +
                        ((1 - $heatWithPower) * (1 - $isHomeAllDay) * 250);
        $estimateWater = ($waterWithPower * 1645) + ((1 - $waterWithPower) * 150);
        $estimateCook = ($cookWithPower * 546) + ((1 - $cookWithPower) * 73);
        $estimateAirCon = (657 * $nrAirCon);
        $estimateFridges = (200 * $nrFridges);
        $estimateWashers = (83.2 * $nrWashers * (1/sqrt($nrOccupants)));
        $estimateComputers = (146 * $nrComputers * (1/sqrt($nrOccupants)));
        $estimateEntertainment = (144 * $nrEntertainment * (1/sqrt($nrOccupants)));
        $estimateDishwashers = (547.5 * $nrDishwashers);

        $totalEstimateYear = ceil($estimateLights + $estimateHeat + $estimateWater +
                             $estimateCook + $estimateAirCon + $estimateFridges +
                             $estimateWashers + $estimateComputers + $estimateEntertainment +
                             $estimateDishwashers);
        return $totalEstimateYear;
    }
    // Employee Private functions
    /**
     * @param int $meterID
     * @return int
     */
    private static function calculateMeterEnergyEstimate(int $meterID): int
    {
        $estimationData = DB::table('estimations')->where('meter_id', $meterID)->first();
        if ($estimationData) 
        {
            $nrOccupants = $estimationData->nbr_occupants;
            $isHomeAllDay = $estimationData->is_home_all_day;
            $heatWithPower = $estimationData->heat_with_power;
            $waterWithPower = $estimationData->water_with_power;
            $cookWithPower = $estimationData->cook_with_power;
            $nrAirCon = $estimationData->nbr_air_con;
            $nrFridges = $estimationData->nbr_fridges;
            $nrWashers = $estimationData->nbr_washers;
            $nrComputers = $estimationData->nbr_computers;
            $nrEntertainment = $estimationData->nbr_entertainment;
            $nrDishwashers = $estimationData->nbr_dishwashers;
        }
        else
        {
            return 0;
        }
        // Lowered
        $estimateLights = 324;
        $estimateHeat = ($heatWithPower * $isHomeAllDay * 10000) +
                        ($heatWithPower * (1 - $isHomeAllDay) * 5000) +
                        ((1 - $heatWithPower) * $isHomeAllDay * 500) +
                        ((1 - $heatWithPower) * (1 - $isHomeAllDay) * 250);
        $estimateWater = ($waterWithPower * 1645) + ((1 - $waterWithPower) * 150);
        $estimateCook = ($cookWithPower * 546) + ((1 - $cookWithPower) * 73);
        $estimateAirCon = (657 * $nrAirCon);
        $estimateFridges = (200 * $nrFridges);
        $estimateWashers = (83.2 * $nrWashers * (1/sqrt($nrOccupants)));
        $estimateComputers = (146 * $nrComputers * (1/sqrt($nrOccupants)));
        $estimateEntertainment = (144 * $nrEntertainment * (1/sqrt($nrOccupants)));
        $estimateDishwashers = (547.5 * $nrDishwashers);

        $totalMeterEstimateYear = ceil($estimateLights + $estimateHeat + $estimateWater +
                             $estimateCook + $estimateAirCon + $estimateFridges +
                             $estimateWashers + $estimateComputers + $estimateEntertainment +
                             $estimateDishwashers); // We always round up!

        $estimationTotal = Estimation::where('meter_id', $meterID)->pluck('estimation_total')->first();
        $estimationTotal = collect($estimationTotal)->flatten()->all();
        if ($estimationTotal == $totalMeterEstimateYear)
        {
            return $totalMeterEstimateYear;
        }
        else
        {
            DB::table('estimations')->where('meter_id', $meterID)->update(array('estimation_total' => $totalMeterEstimateYear));
            return $totalMeterEstimateYear;
        }
    }
}
?>