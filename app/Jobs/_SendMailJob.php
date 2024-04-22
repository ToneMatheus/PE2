<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

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
    protected $pdfView;
    protected $pdfParams;

    public function __construct($mailTo, $mailableClass, $mailableClassParams, $pdfView, $pdfParams, $jobRunID, $invoiceID){
        $this->mailTo = $mailTo;
        $this->mailableClass = $mailableClass;
        $this->mailableClassParams = unserialize($mailableClassParams);
        $this->JobRunId = $jobRunID;
        $this->invoiceID = $invoiceID;
        $this->pdfView = $pdfView;
        $this->pdfParams = unserialize($pdfParams);
    }

    public function handle(){

        if ($this->pdfView != null){
            $pdf = Pdf::loadView($this->pdfView, [
                ...$this->pdfParams
            ], [], 'utf-8');
            $pdfData = $pdf->output();
            array_push($this->mailableClassParams, $pdfData);
        }

        if (Mail::to($this->mailTo)->send(new $this->mailableClass(...$this->mailableClassParams)) == null){
            Log::error("Unable to send mail for invoice with ID: ". $this->invoiceID);
            $this->logError($this->invoiceID, "Unable to send mail");
        }
        else{
            $this->logInfo($this->invoiceID , "Succesfully sent mail.");
        } 
    }
}
