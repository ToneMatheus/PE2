<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EditTickets;

class EditController extends Controller
{
    public function index()
    {
        // Retrieve only active tickets
        $tickets = EditTickets::where('active', '1')->get();
        
        // Pass the retrieved tickets to the view
        return view('customertickets.Edit', compact('tickets'));
    }

    // public function update(Request $request, $id)
    // {
    //     // Validate the incoming request data
    //     $request->validate([
    //         'name' => 'required|string',
    //         'email' => 'required|email',
    //         'issue' => 'required|string',
    //         'description' => 'required|string',
    //     ]);

    //     // Find the ticket by its ID
    //     $ticket = Ticket::findOrFail($id);

    //     // Update the ticket with the new data from the request
    //     $ticket->update([
    //         'name' => $request->input('name'),
    //         'email' => $request->input('email'),
    //         'issue' => $request->input('issue'),
    //         'description' => $request->input('description'),
    //     ]);

    //     // Redirect back with a success message
    //     return redirect()->back()->with('success', 'Ticket updated successfully!');
    // }
}