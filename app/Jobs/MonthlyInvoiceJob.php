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

use App\Mail\MonthlyInvoiceMail;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Traits\cronJobTrait;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

use App\Services\InvoiceFineService;

class MonthlyInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, cronJobTrait;
    protected $now;
    protected $year;
    protected $month;
    protected $mID;

    public function __construct($mID)
    {
        $this->now = config('app.now');
        $this->month = $this->now->format('m');
        $this->year = $this->now->format('Y');
        $this->mID = $mID;
    }

    public function handle()
    {
        $customer = User::join('Customer_contracts as cc', 'users.id', '=', 'cc.user_id')
        ->join('Customer_addresses as ca', 'users.id', '=', 'ca.user_id')
        ->join('Addresses as a', 'ca.Address_id', '=', 'a.id')
        ->join('Meter_addresses as ma', 'a.id', '=', 'ma.address_id')
        ->join('Meters as m', 'ma.meter_id', '=', 'm.id')
        ->where('m.type', '=', 'Electricity')
        ->where('m.status', '=', 'Installed')
        ->where('m.is_smart', '=', '0')
        ->where('m.id', '=', $this->mID)
        ->select('users.id as uID', 'cc.id as ccID', 'm.id as mID', 'cc.start_date as startContract')
        ->first();

        $invoiceDate = $this->now;
        $dueDate = $invoiceDate->copy()->endOfMonth();

        $invoiceRunJob = new InvoiceRunJob();
        $invoiceRunJob->generateMonthlyInvoice($customer, $invoiceDate, $dueDate);
    }
}

