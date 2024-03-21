<?php

namespace App\Http\Controllers;

use App\Mail\CreditNoteMail;
use App\Models\CreditNote;
use App\Models\CreditNoteLine;
use App\Models\User;
use Illuminate\Http\Request;
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
