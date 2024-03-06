<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class invoice_query_controller extends Controller
{
    public function contracts()
    {

        $results1 = DB::select('select c.ID, c.customerID, c.startDate, c.endDate, c.type, c.price, c.status from customercontract c 
        LEFT JOIN invoice i 
        ON c.ID = i.customerContractID
        WHERE MONTH(c.startDate) = MONTH(CURRENT_DATE())
        AND c.status LIKE \'active\'
        AND c.endDate > CURRENT_DATE()
        AND i.status NOT LIKE \'paid\';');

        $resultsAll = DB::table('customercontract')->get();

        return view("invoice_query",['resultsAll'=>$resultsAll, 'results1'=>$results1]);
    }
}
