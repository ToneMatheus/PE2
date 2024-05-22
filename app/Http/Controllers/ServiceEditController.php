<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket; // Import the Ticket model

class ServiceEditController extends Controller
{

    public function index($id) // Accept the $id parameter
    {
        $ticket = Ticket::findOrFail($id); // Fetch the ticket with the given ID
        return view('tickets.ServiceEdit', compact('ticket'));
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $request->validate([
            'resolution' => 'required|string'
        ]);

        // Find the ticket by its ID
        $ticket = Ticket::findOrFail($id);

        // Update the ticket with the new data from the request
        $ticket->update([
            'resolution' => $request->input('resolution')
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Ticket updated successfully!');
    }
}