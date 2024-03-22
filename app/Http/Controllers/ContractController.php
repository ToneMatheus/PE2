<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contract_product;

class ContractController extends Controller{
    public function index(){
        //$roleID = 


        $contracts = Contract_product::with('product')->get();

        /*$contracts = Contract_product::with('product')
        ->where('userID', '=', 1)
        ->get();*/

        /*
        $contracts = Contract_product::with('product', 'customer_contract')
        ->where('user_id','=',1)
        ->get();
        */



        $contracts = Contract_product::with('product', 'customer_contract')
        ->whereHas('customer_contract', function ($query) {
            $query->where('user_id', '=', 3);//TODO vervang door sessievariabele van id van ingelogde user
        })
        ->get();
        

        return view('Customer.contractOverview', compact('contracts'));
    }

}


?>