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
        $this->middleware('SetLocale');
    }

    public function index()
    {
        $tickets = TicketOverview::all();
        return view('ticketoverview.index', compact('tickets'));
    }
}
