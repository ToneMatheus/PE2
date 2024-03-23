<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contract_product;
use Illuminate\Support\Facades\Auth;

class ContractController extends Controller{
    public function index(){
        $contracts = Contract_product::with('product')->get();
        $id = Auth::user()->id;


        $contracts = Contract_product::with('product', 'customer_contract')
        ->whereHas('customer_contract', function ($query) use ($id) {
            $query->where('user_id', '=', $id);
        })
        ->get();
        

        return view('Customer.contractOverview', compact('contracts'));
    }

}
?>