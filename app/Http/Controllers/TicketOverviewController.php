<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TicketOverview;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;


class TicketOverviewController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        $user_id = Auth::id();
        $tickets = TicketOverview::where('user_id', $user_id)->get();

        return view('Tickets.ticketoverview', compact('tickets'));
    }
}
