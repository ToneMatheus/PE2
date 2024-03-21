<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Estimation;

class EstimationController extends Controller
{
    // TEMP
    public function showButton()
    {
        return view('Invoices.testingEstimation');
    }
    public function generateOneInvoice(Request $request)
    {
        $request->validate([
            'customerID' => 'required|integer|min:1|max:10',
        ], [
            'customerID.min' => 'This field must be at least 1.','customerID.max' => 'There are only 2 customers ATM, sorry!',
        ]);
        $customer = $request->input('customerID');
        $estimationResult = DB::table('users as u')
            ->join('customer_addresses as ca', 'ca.user_id', '=', 'u.id')
            ->join('addresses as a', 'ca.address_id', '=', 'a.id')
            ->join('meter_addresses as ma', 'a.id', '=', 'ma.address_id')
            ->join('meters as m', 'ma.meter_id', '=', 'm.id')
            ->join('estimations as e', 'e.meter_id', '=', 'm.id')
            ->where('u.id', '=', $customer)
            ->select('e.estimation_total')
            ->first();
        dd($estimationResult);
        dd($this->CalculateMonthlyEnergyAmount($customerID));
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
    // Employee Public functions
    /**
     * @param int $meterID
     * @return float
     */
    public static function CalculateMonthlyDollarAmount(int $meterID): float
    {
        // $meterValue = DB::table('index_values')->orderby('reading_value','desc')->where('meter_id', $meterID)->take(2)->pluck('reading_value')->toArray();
        // if (count($meterValue) >= 2)
        // {
        //     $difference = abs($meterValue[0] - $meterValue[1]);
        //     if ($difference > 0)
        //     {
        //         $totalMeterEstimateYear = $difference;
        //     }
        //     else
        //     {
        //         $totalMeterEstimateYear = EstimationController::calculateMeterEnergyEstimate($meterID);
        //     }
        // }
        // else
        // {
        //     $totalMeterEstimateYear = EstimationController::calculateMeterEnergyEstimate($meterID);
        // }
        $totalMeterEstimateYear = EstimationController::calculateMeterEnergyEstimate($meterID);
        $EstimationMonthly = $totalMeterEstimateYear/12;
        $moneyAmountMonthly = number_format($EstimationMonthly*0.25,2);
        return $moneyAmountMonthly;
    }
    /**
     * @param int $meterID
     * @return int
     */
    public static function CalculateMonthlyEnergyAmount(int $meterID): int
    {
        // $meterValue = DB::table('index_values')->orderby('reading_value','desc')->where('meter_id', $meterID)->take(2)->pluck('reading_value')->toArray();
        // if (count($meterValue) >= 2)
        // {
        //     $difference = abs($meterValue[0] - $meterValue[1]);
        //     if ($difference > 0)
        //     {
        //         $totalMeterEstimateYear = $difference;
        //     }
        //     else
        //     {
        //         $totalMeterEstimateYear = EstimationController::calculateMeterEnergyEstimate($meterID);
        //     }
        // }
        // else
        // {
        //     $totalMeterEstimateYear = EstimationController::calculateMeterEnergyEstimate($meterID);
        // }
        $totalMeterEstimateYear = EstimationController::calculateMeterEnergyEstimate($meterID);
        $EstimationMonthly = $totalMeterEstimateYear/12;
        return $EstimationMonthly;
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

        //$estimationTotal = DB::table('estimations')->where('meter_id', $meterID)->pluck('estimation_total');
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