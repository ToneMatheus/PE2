<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\escalateTickets;

class escalateTicketController extends Controller
{
    public function index()
    {
        // Retrieve only active tickets
        $tickets = escalateTickets::where('active', '1')->get();
        
        // Pass the retrieved tickets to the view
        return view('customertickets.escalateTickets', compact('tickets'));
    }
}