<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ManagerTicketOverview;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;


class ManagerTicketOverviewController extends Controller
{
    public function index(Request $request)
    {
        // Retrieve the desired locale from the request or any other source
        $locale = $request->input('locale', 'en_US'); // Example: Default to 'en_US' if not provided in the request

        // Set the application locale dynamically
        app()->setLocale($locale);

        // Your controller logic here
        $tickets = ManagerTicketOverview::all();
        return view('tickets.ManagerTicketOverview', compact('tickets'));
    }

    public function closeTicket($id){
        // Find the ticket by its ID
        $ticket = Ticket::findOrFail($id);
    
        // Log the current value of is_solved
        Log::info('Received ticket ID: ' . $ticket->id);
        Log::info('Current value of is_solved: ' . $ticket->is_solved);
    
        // Update the ticket to mark it as solved
        $ticket->update([
            'is_solved' => 1
        ]);
    
        // Log the updated value of is_solved
        Log::info('Updated value of is_solved: ' . $ticket->is_solved);
    
        // Store a success message in the session
        session()->flash('success', 'Ticket successfully closed.');
    
        // Redirect back to the previous page
        return redirect()->back();
    }
    
    
    
    
    
}
