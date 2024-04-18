<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contract_product;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;

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

    public function download(Request $request){
        $id = Auth::user()->id;
        $contract = Contract_product::with('product', 'customer_contract')
        ->whereHas('customer_contract', function ($query) use ($id, $request) {
            $query->where('id', '=', $request->id);
        })
        ->first();


        //$pdf = FacadePdf::loadView('pdf.invoice_pdf', compact('invoice', 'name'));
        $pdf = FacadePdf::loadView('Customer.contract_pdf', compact('contract'));
        return $pdf->download('contract.pdf');
    }

}
?>