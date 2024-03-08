<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HistoryTickets;

class HistoryController extends Controller
{
    public function index()
    {
        // Retrieve only active tickets
        $tickets = HistoryTickets::where('active', '1')->get();
        
        // Pass the retrieved tickets to the view
        return view('customertickets.history', compact('tickets'));
    }
}
