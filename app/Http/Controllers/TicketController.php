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
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Notifications\TicketOpenNotification;

class TicketController extends Controller
{
    public function showForm(): View
    {
        $title = 'Create Ticket';
        return view('Support_Pages/create-ticket', ['title' => $title]);
    }

    public function store(Request $request): Redirector|RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|max:64',
            'email' => 'required|max:64|email',
            'issue' => 'required|max:64',
            'description' => 'required',
        ]);

        $user = Auth::check() ? Auth::user() : null;

        $roleUser = null;
        $roleName = null;

        if ($user) {
            $roleUser = DB::table('user_roles')->where('user_id', $user->id)->first();
            if ($roleUser) {
                $role = DB::table('roles')->where('id', $roleUser->role_id)->first();
                if ($role) {
                    $roleName = $role->role_name;
                }
            }
        }

        // create ticket
        $ticket = new Ticket();
        $ticket->name = $validatedData['name'];
        $ticket->email = $validatedData['email'];
        $ticket->issue = $validatedData['issue'];
        $ticket->description = $validatedData['description'];
        $ticket->active = 0;
        $ticket->role = $roleName;
        $ticket->user_id = $user ? $user->id : null;
        $ticket->save();

        return redirect()->route('show-ticket')->with(['ticket' => $ticket]);
    }

    public function showSubmittedTicket(): View
    {
        $ticket = session('ticket');

        return view('Support_Pages/submitted-ticket', ['ticket' => $ticket]);
    }
}
