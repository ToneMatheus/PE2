<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerTickets;

class CustomerTicketController extends Controller
{

    public function index()
    {
        $tickets = CustomerTickets::all();
        return view('customertickets.index', compact('tickets'));
    }
    
}