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
        return view('create-ticket');
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
        $customerticket = new Ticket();
        $customerticket->name = $validatedData['name'];
        $customerticket->email = $validatedData['email'];
        $customerticket->issue = $validatedData['issue'];
        $customerticket->description = $validatedData['description'];
        $customerticket->active = 0;
        $customerticket->role = auth()->check() ? auth()->user()->role : null;
        $customerticket->save();

        return redirect()->route('show-ticket')->with(['customerticket' => $customerticket]);
    }

    public function showSubmittedTicket(): View
    {
        $customerticket = session('customerticket');

        return view('submitted-ticket', ['customerticket' => $customerticket]);
    }
}
