<?php

namespace App\Jobs;

use App\Mail\weekAdvanceReminder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use App\Models\Invoice;

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
        //Query which invoices have not been paid yet and are due in 1 week
            /*$unpaidInvoices = DB::select('SELECT i.id FROM invoices i 
            WHERE (i.status NOT LIKE \'paid\' AND i.status NOT LIKE \'pending\')
            AND i.due_date = DATE_ADD(CURDATE(), INTERVAL 7 DAY);');*/
        $unpaidInvoices = Invoice::select('id')
        ->whereNotIn('status', ['paid', 'pending'])
        ->whereDate('due_date', '=', now()->addDays(7)->toDateString())
        ->get()
        ->pluck('id')
        ->toArray();

        //For each ID, send mail
        if (!empty($unpaidInvoices))
        {
            foreach ($unpaidInvoices as $unpaidInvoice)
            {
                $this->sendMail($unpaidInvoice);
            }
        }
    }

    public function sendMail(int $invoiceID)
    {
        //gather data of users: name, e-mail
        //gather data of lines of invoice
        $invoice = Invoice::find($invoiceID);
        $invoice_info = $invoice->invoice_lines;

        $total_amount = Invoice::select('total_amount')
            ->where('id', $invoiceID)
            ->first();

        $user_info = Invoice::select('users.email', 'users.first_name', 'users.last_name')
            ->leftJoin('customer_contracts', 'invoices.customer_contract_id', '=', 'customer_contracts.id')
            ->leftJoin('users', 'customer_contracts.user_id', '=', 'users.id')
            ->where('invoices.id', $invoiceID)
            ->first();

        //DELETE IF NO ISSUES:
        //$total_amount = DB::select('SELECT i.total_amount FROM invoices i 
        //WHERE i.id = '.$invoiceID.';');

        /*$user_info = DB::select('SELECT u.email, u.first_name, u.last_name FROM invoices i 
        LEFT JOIN customer_contracts cc 
        ON i.customer_contract_id = cc.id 
        LEFT JOIN users u 
        ON cc.user_id = u.id
        WHERE i.id = '.$invoiceID.';');*/

        //return new \App\Mail\weekAdvanceReminder($invoice_info, $total_amount, $user_info);

        Mail::to('niki.de.visscher@gmail.com')->send(new weekAdvanceReminder($invoice_info, $total_amount, $user_info));
    }
}
