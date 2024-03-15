<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class TicketController extends Controller
{
    public function showForm(): View
    {
        return view('Support_Pages/create-ticket');
    }

    public function store(Request $request): Redirector|RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|max:64',
            'email' => 'required|max:64|email',
            'issue' => 'required|max:64',
            'description' => 'required',
        ]);

        $user = Auth::user();

        // create ticket
        $ticket = new Ticket();
        $ticket->name = $validatedData['name'];
        $ticket->email = $validatedData['email'];
        $ticket->issue = $validatedData['issue'];
        $ticket->description = $validatedData['description'];
        $ticket->active = 0;
        $ticket->role = auth()->check() ? auth()->user()->role : null;
        $ticket->user_id = auth()->check() ? auth()->user()->id : null;
        $ticket->save();

        return redirect()->route('show-ticket')->with(['ticket' => $ticket]);
    }

    public function showSubmittedTicket(): View
    {
        $ticket = session('ticket');

        return view('Support_Pages/submitted-ticket', ['ticket' => $ticket]);
    }
}
