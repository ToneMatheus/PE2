<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketDashboardController extends Controller
{
    public function index(){
        $tickets = Ticket::with('ticket')->where('active',1)->get();

        $tickets_closed = Ticket::with('ticket')->where('active',0)->get();

        return view('Support_Pages.ticketDashboard', compact("tickets"), compact("tickets_closed"));
    }


}
