<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CreditNote;
use Illuminate\Support\Facades\DB;

class PayoutsController extends Controller
{
    public function showPayouts(){
        $creditNotes = CreditNote::where('is_applied', '=', 1)
        ->where('credit_notes.is_active', '=', 1)
        ->join('users as u', 'u.id', '=', 'credit_notes.user_id')
        ->select('u.id as uID', DB::raw("CONCAT(u.first_name, ' ', u.last_name) AS full_name"), 'credit_notes.invoice_id as invoiceID', 'credit_notes.amount', 'credit_notes.id')
        ->get();

        return view('invoices.payouts', ['creditNotes' => $creditNotes]);
    }

    public function processPayout($id){
        CreditNote::where('id', $id)
        ->update(['is_active' => 0]);

        return redirect()->route('payouts');
    }
}
