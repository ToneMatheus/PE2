<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\AnnualInvoiceJob;
use App\Jobs\MonthlyInvoiceJob;
use App\Models\Customer_Address;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Meter;
use Illuminate\View\View;

class ManualInvoiceController extends Controller
{
    function showManualInvoice() {
        $customers = User::join('customer_contracts as cc', 'cc.user_id', '=', 'users.id')
            ->where('cc.status', '=', 'active')
            ->get();
    
        $meters = Customer_Address::join('addresses as a', 'a.id', '=', 'customer_addresses.address_id')
        ->join('meter_addresses as ma', 'ma.address_id', '=', 'a.id')
        ->join('meters as m', 'm.id', '=', 'ma.meter_id')
        ->get();

        return view('invoices.manualInvoice', compact('customers'), compact('meters'));
    }

    function processManualInvoice(Request $request) {
        if($request->input('action') == 'annual'){
            dispatch(new AnnualInvoiceJob($request->input('meter')));
        } else {
            dispatch(new MonthlyInvoiceJob($request->input('meter')));
        }

        return redirect()->route('manualInvoice');
    }
}
