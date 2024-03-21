<?php

namespace App\Http\Controllers;

use App\Models\CreditNote;
use App\Models\User;
use Illuminate\Http\Request;

class CreditNoteController extends Controller
{
    //
    public function index(){
        $creditNotes = CreditNote::all();
        return view('credit-notes.index', compact('creditNotes'));
    }

    public function create(){
        $customers = User::all();
        return view('credit-notes.create', compact('customers'));
    }

    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'type' => 'required|string',
            'description' => 'required|string',
            'amount' => 'required|numeric',
            'customer_id' => 'required|exists:customers,id',
        ]);

        // Store the credit note
        CreditNote::create($request->all());

        return redirect()->route('credit-notes.index')->with('success', 'Credit note created successfully.');
    }
}
