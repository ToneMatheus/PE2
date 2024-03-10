<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class invoice_query_controller extends Controller
{
    public function contracts()
    {

        $results1 = DB::select('select c.id AS cont_ID, c.user_id, c.start_date, c.end_date, c.type, c.price, c.status as cont_status, 
        IFNULL(i.id, \'NULL\' ) AS inv_ID 
        from customer_contracts c 
        LEFT JOIN invoices i 
        ON c.ID = i.customer_contract_id
        WHERE MONTH(c.start_date) = MONTH(CURRENT_DATE())
        AND c.status LIKE \'active\'
        AND c.end_date > CURRENT_DATE()
        AND i.id IS NULL;');

        $resultsAll = DB::table('customer_contracts')->get();

        return view("invoice_query",['resultsAll'=>$resultsAll, 'results1'=>$results1]);
    }
}
