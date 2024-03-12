<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstimationController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return int
     */
    public static function CalculateMonthlyAmount(int $meterID): float
    {
        $meterValue = DB::table('index_values')->orderby('reading_value','desc')->where('meter_id', $meterID)->take(2)->pluck('reading_value')->toArray();
        if (count($meterValue) >= 2)
        {
            $difference = abs($meterValue[0] - $meterValue[1]);
            if ($difference > 0)
            {
                $totalMeterEstimateYear = $difference;
            }
            else
            {
                $totalMeterEstimateYear = EstimationController::calculateMeterEnergyEstimate($meterID);
            }
        }
        else
        {
            $totalMeterEstimateYear = EstimationController::calculateMeterEnergyEstimate($meterID);
        }
        $EstimationMonthly = $totalMeterEstimateYear/12;
        $moneyAmountMonthly = number_format($EstimationMonthly*0.25,2);
        return $moneyAmountMonthly;
    }
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function showGuestEnergyEstimate(Request $request)
    {
        $totalEstimateYear = $this->calculateGuestTotalEnergyEstimate($request);

        return view('EstimationGuest', ['totalEstimateYear' => $totalEstimateYear]);
    }
    public function showGuestForm()
    {
        return view('EstimationGuest');
    }
    /**
     * @param \Illuminate\Http\Request $request
     * @return int
     */
    private function calculateGuestTotalEnergyEstimate(Request $request): int
    {
        $request->validate([
            'customerID' => 'required|integer|min:1|max:15',
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
        // TO BE CHANGED
        $estimateLights = 324;
        $estimateHeat = ($heatWithPower * $isHomeAllDay * 20000) +
                        ($heatWithPower * (1 - $isHomeAllDay) * 10000) +
                        ((1 - $heatWithPower) * $isHomeAllDay * 1000) +
                        ((1 - $heatWithPower) * (1 - $isHomeAllDay) * 500);
        $estimateWater = ($waterWithPower * 2190) + ((1 - $waterWithPower) * 300);
        $estimateCook = ($cookWithPower * 1095) + ((1 - $cookWithPower) * 73);
        $estimateAirCon = (1314 * $nrAirCon);
        $estimateFridges = (400 * $nrFridges);
        $estimateWashers = (83.2 * $nrWashers * $nrOccupants);
        $estimateComputers = (146 * $nrComputers * $nrOccupants);
        $estimateEntertainment = (144 * $nrEntertainment * $nrOccupants);
        $estimateDishwashers = (547.5 * $nrDishwashers);

        $totalEstimateYear = $estimateLights + $estimateHeat + $estimateWater +
                             $estimateCook + $estimateAirCon + $estimateFridges +
                             $estimateWashers + $estimateComputers + $estimateEntertainment +
                             $estimateDishwashers;

        return $totalEstimateYear;
    }
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
        // TO BE CHANGED
        $estimateLights = 324;
        $estimateHeat = ($heatWithPower * $isHomeAllDay * 20000) +
                        ($heatWithPower * (1 - $isHomeAllDay) * 10000) +
                        ((1 - $heatWithPower) * $isHomeAllDay * 1000) +
                        ((1 - $heatWithPower) * (1 - $isHomeAllDay) * 500);
        $estimateWater = ($waterWithPower * 2190) + ((1 - $waterWithPower) * 300);
        $estimateCook = ($cookWithPower * 1095) + ((1 - $cookWithPower) * 73);
        $estimateAirCon = (1314 * $nrAirCon);
        $estimateFridges = (400 * $nrFridges);
        $estimateWashers = (83.2 * $nrWashers * $nrOccupants);
        $estimateComputers = (146 * $nrComputers * $nrOccupants);
        $estimateEntertainment = (144 * $nrEntertainment * $nrOccupants);
        $estimateDishwashers = (547.5 * $nrDishwashers);

        $totalMeterEstimateYear = ceil($estimateLights + $estimateHeat + $estimateWater +
                             $estimateCook + $estimateAirCon + $estimateFridges +
                             $estimateWashers + $estimateComputers + $estimateEntertainment +
                             $estimateDishwashers); // We always round up!

        $estimationTotal = DB::table('estimations')->where('meter_id', $meterID)->pluck('estimation_total');
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