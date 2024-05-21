<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use App\Models\details;
use App\Models\Ticket;

class DetailsController extends Controller
{
    public function index($id) // Accept the $id parameter
    {
        $ticket = Ticket::findOrFail($id); // Fetch the ticket with the given ID
        return view('tickets.details', compact('ticket'));
    }
}