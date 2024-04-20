<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Traits\cronJobTrait;

class _SendMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, cronJobTrait;

    protected $mailTo;
    protected $mailableClass;
    protected $mailableClassParams;
    protected $invoiceID;

    public function __construct($mailTo, $mailableClass, $mailableClassParams, $jobRunID, $invoiceID){
        $this->mailTo = $mailTo;
        $this->mailableClass = $mailableClass;
        $this->mailableClassParams = unserialize($mailableClassParams);
        $this->JobRunId = $jobRunID;
        $this->invoiceID = $invoiceID;
    }

    public function handle(){
        if (Mail::to($this->mailTo)->send(new $this->mailableClass(...$this->mailableClassParams)) == null){
            Log::error("Unable to send mail for invoice with ID: ". $this->invoiceID);
            $this->logError($this->invoiceID, "Unable to send mail");
        }
        else{
            $this->logInfo($this->invoiceID , "Succesfully sent mail.");
        } 
    }
}
