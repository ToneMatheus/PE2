<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class unpaid_invoice_query_controller extends Controller
{
    public function unpaidInvoices()
    {
        $resultsAll = DB::table('invoices')->get();

        $results1 = DB::select('SELECT * FROM invoices i
                WHERE (i.status NOT LIKE \'paid\' AND i.status NOT LIKE \'draft\')
                AND i.due_date < CURRENT_DATE();');

        return view("unpaid_invoice_query",['resultsAll'=>$resultsAll, 'results1'=>$results1]);
    }
}
