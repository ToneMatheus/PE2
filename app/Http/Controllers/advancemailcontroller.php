<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class advancemailcontroller extends Controller
{
    public function index(int $invoiceID = 1)
    {
        $invoice_info = DB::select('SELECT * FROM invoice_lines il 
        WHERE il.id = '.$invoiceID.';');

        $total_amount = DB::select('SELECT i.total_amount FROM invoices i 
        WHERE i.id = '.$invoiceID.';');

        $user_info = DB::select('SELECT u.email, u.first_name, u.last_name FROM invoices i 
        LEFT JOIN customer_contracts cc 
        ON i.customer_contract_id = cc.id 
        LEFT JOIN users u 
        ON cc.user_id = u.id
        WHERE i.id = '.$invoiceID.';');

        return new \App\Mail\weekAdvanceReminder($invoice_info, $total_amount, $user_info);
    }
}
