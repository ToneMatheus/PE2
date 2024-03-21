<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contract_product;

class ContractController extends Controller{
    public function index(){
        $contracts = Contract_product::with('product')->get();

        /*$contracts = Contract_product::with('product')
        ->where('userID', '=', 1)
        ->get();*/
        

        return view('Customer.contractOverview', compact('contracts'));
    }

}


?>