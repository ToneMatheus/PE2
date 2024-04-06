<?php

namespace App\Http\Controllers;

use App\Mail\CreditNoteMail;
use App\Models\CreditNote;
use App\Models\CreditNoteLine;
use App\Models\Invoice;
use App\Models\Invoice_line;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


class CreditNoteController extends Controller
{
    //
    public function index(){
        $creditNotes = CreditNote::all();
        return view('credit-notes.index', compact('creditNotes'));
    }

    public function create(){
        $users = User::all();
        return view('credit-notes.create', compact('users'));
    }

    public function show(){
        return view('credit-notes.invoice_search');
    }

    public function search(Request $request){
        $invoiceNumber = $request->input('invoice_number');

        // Extracting ID from the invoice number
        preg_match('/INV(\d{4})/', $invoiceNumber, $matches);
        
        if(isset($matches[1])) {
            $invoiceId = $matches[1];
            $invoice = Invoice::find($invoiceId);
            return view('credit-notes.invoice_details', compact('invoice', 'invoiceId'));
        } else {
            return "Invalid invoice number format";
        }
    }

    public function refund(Request $request)
    {
        $invoiceId = str_pad($request->invoice_id, 4, '0', STR_PAD_LEFT);
        $LineItemIdCollection = $request->input('line_items');
        $lineItems = collect();

        foreach($LineItemIdCollection as $id){
            $lineItems->push(Invoice_line::find($id));
        }
        $user = DB::table('invoices')
        ->join('customer_contracts', 'invoices.customer_contract_id', '=', 'customer_contracts.id')
        ->join('users', 'customer_contracts.user_id', '=', 'users.id')
        ->where('invoices.id', '=' , $invoiceId)
        ->select('users.*')
        ->first();

        $creditNote = CreditNote::create([
            'type' => 'Refund credit note',
            'status' => 1,
            'description' => 'This is a credit note for invoice INV'.$invoiceId,
            'user_id' => $user->id,
        ]);


        //Store a total of all prices so we can add it later to the Credit Note entity
        $sum = 0;
        foreach ($lineItems as $item) {
            $sum += $item->amount;
            CreditNoteLine::create([
                'credit_note_id' => $creditNote->id,
                'product' => $item->type,
                'quantity' => 1,
                'price' => $item->unit_price,
                //Calculate the amount per line by multiplying the price per unit with the quantity of the product
                'amount' => $item->amount,
            ]);
        }
        //Save the sum of all prices on the credit note Entity
        $creditNote->amount = $sum;
        $creditNote->save();

        //Mail credit note info
        $creditNoteLines = CreditNoteLine::where('credit_note_id', '=', $creditNote->id)->get();
        Mail::to('finnvc99@gmail.com')->send(new CreditNoteMail($creditNoteLines, $user));

        return redirect()->back()->with('success', 'Refund successful');
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'type' => 'required|string',
            'description' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'products.*' => 'required|string',
            'quantities.*' => 'required|numeric|min:1',
            'prices.*' => 'required|numeric|min:0',
        ]);
    
        $creditNote = CreditNote::create([
            'type' => $validatedData['type'],
            'status' => 1,
            'description' => $validatedData['description'],
            'user_id' => $validatedData['user_id'],
        ]);
    
        foreach ($validatedData['products'] as $index => $product) {
            CreditNoteLine::create([
                'credit_note_id' => $creditNote->id,
                'product' => $product,
                'quantity' => $validatedData['quantities'][$index],
                'price' => $validatedData['prices'][$index],
                //Calculate the amount per line by multiplying the price per unit with the quantity of the product
                'amount' => $validatedData['quantities'][$index]*$validatedData['prices'][$index],
            ]);
        }

        $creditNoteLines = CreditNoteLine::where('credit_note_id', '=', $creditNote->id)->get();
        $user = User::find($validatedData['user_id']);
        Mail::to('finnvc99@gmail.com')->send(new CreditNoteMail($creditNoteLines, $user));

        return redirect()->route('credit-notes.index')->with('success', 'Credit note created successfully.');
    }
}
