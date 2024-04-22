<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Carbon;
use App\Models\{
    User, 
    Invoice,
    Contract_product,
    Product,
    Invoice_line,
    CreditNote,
    Estimation,
    Index_Value,
    Discount,
    Meter
};

//this job calculates data for the final settlement invoice and stores it in database
//the job only handles the invoice side of a leaving customer
//how do we tell the job which meter?
class FinalSettlementJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $now;
    protected $year;
    protected $month;

    protected $meterID;

    public function __construct($meterID)
    {
        $this->now = config('app.now');
        $this->month = $this->now->format('m');
        $this->year = $this->now->format('Y');
        $this->meterID = $meterID;
    }

    public function handle()
    {
        //set up current date
        $now = $this->now->copy();
        $month = $this->month;
        $year = $this->year;

        $meterID = $this->meterID;

        $meter = Meter::find($meterID);

        // 1) check if we have meter reading
        //      if not, reminder system
        if ($meter->expecting_reading)
        {
            //reminder system or other actions
        }


        // 2) calculate amount based on actual consumption
        // 3) store in database
    }
}
