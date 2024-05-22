<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserEdit;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use App\Models\Ticket;



class UserEditController extends Controller
{

    public function index($id) // Accept the $id parameter
    {
        $ticket = Ticket::findOrFail($id); // Fetch the ticket with the given ID
        return view('tickets.UserEdit', compact('ticket'));
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $request->validate([
            'issue' => 'required|string',
            'description' => 'required|string'
        ]);

        // Find the ticket by its ID
        $ticket = Ticket::findOrFail($id);

        // Update the ticket with the new data from the request
        $ticket->update([
            'issue' => $request->input('issue'),
            'description' => $request->input('description')
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Ticket updated successfully!');
    }
}
