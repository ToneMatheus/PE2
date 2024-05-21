<?php

namespace App\Jobs;

use App\Models\Estimation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

use App\Models\Invoice;
use App\Models\Invoice_line;
use App\Models\User;
use App\Models\CreditNote;
use App\Models\Meter;
use App\Models\Index_Value;

use App\Mail\MonthlyInvoiceMail;
use App\Traits\cronJobTrait;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

use App\Services\InvoiceFineService;

class UpdateEstimationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, cronJobTrait;


    protected $domain = "http://127.0.0.1:8000"; //change later
    protected $now;
    protected $year;
    protected $month;

    public function __construct($logLevel = null)
    {
        $this->LoggingLevel = $logLevel;
        $this->now = config('app.now');
        $this->month = $this->now->format('m');
        $this->year = $this->now->format('Y');
    }

    public function handle()
    {
        $this->jobStart();

        $now = $this->now->copy();
        $month = $this->month;
        $year = $this->year;
        // Get all meters
        $meters = Meter::select('meters.id as meter_id')->where("is_smart", "=", 0)
                    ->get()
                    ->pluck('meter_id')
                    ->toArray();
        // dd($meters);
        foreach($meters as $meterID){
            // Check if estimation table has this meter.
            if(sizeof(Estimation::get()->where('meter_id', '=', $meterID)->toArray()) == 0){
                // Validation error not enough data to make estimation.
                $this->logError(null,'Exception caught: ' . "Estimation Validation Error Code 1: No estimation data found for meter with id: $meterID.");
            }else{
                // Update this meters values.
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
                    $this->calculateMeterEnergyEstimate($meterID);
                }
            }
        }
        $this->jobCompletion("Completed invoice run job");
    }
    public function calculateMeterEnergyEstimate(int $meterID): int
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

