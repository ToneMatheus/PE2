<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ServiceTicketOverview; // Add this line to import the ServiceTicketOverview model

class ServiceTicketOverviewController extends Controller
{

    public function index()
    {
        $employeeID = Auth::id();
        $tickets = ServiceTicketOverview::where('employee_id', $employeeID)->get();

        return view('Tickets.ServiceTicketOverview', compact('tickets'));
    }

}
