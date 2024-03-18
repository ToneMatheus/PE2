<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\DB;

use App\Models\Invoice;
use App\Models\Invoice_line;
use App\Models\User;

class WeekAdvanceReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
    }

    public function sendMail(int $invoiceID = 1)
    {
        $invoice = Invoice::find($invoiceID);
        $invoice_info = $invoice->invoice_lines;

        //$total_amount = DB::select('SELECT i.total_amount FROM invoices i 
        //WHERE i.id = '.$invoiceID.';');

        $total_amount = Invoice::select('total_amount')
            ->where('id', $invoiceID)
            ->first();

        $user_info = DB::select('SELECT u.email, u.first_name, u.last_name FROM invoices i 
        LEFT JOIN customer_contracts cc 
        ON i.customer_contract_id = cc.id 
        LEFT JOIN users u 
        ON cc.user_id = u.id
        WHERE i.id = '.$invoiceID.';');

        return new \App\Mail\weekAdvanceReminder($invoice_info, $total_amount, $user_info);
    }
}
