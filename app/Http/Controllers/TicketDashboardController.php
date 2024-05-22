<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Employee_Ticket;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Psy\Readline\Hoa\Console;

class TicketDashboardController extends Controller
{
    public function index(){
        $user = Auth::check() ? Auth::user() : null;

        //haal alle tickets op die nog niet toegewezen zijn aan iemand en nog open zijn
        //$tickets = Ticket::with('ticket')->where('active',1)->get();
        $tickets = Ticket::whereDoesntHave('employee_Tickets')->where('status', 0)->get();

        //haal alle tickets op die gesloten zijn
        //$tickets_closed = Ticket::with('ticket')->where('status',1)->get();
        $tickets_closed = Ticket::where('status', 1)->get();
        
        //haal alle tickets op die toegewezen zijn aan de ingelogde gebruiker
        // $own_tickets = Ticket::whereHas('employee_Tickets', function($query) use ($user) {
        //     $query->where('employee_profile_id', $user->employee_profile->id);
        // })->where('status', 0)->get();

        $own_tickets = Ticket::whereHas('employee_Tickets', function($query) use ($user) {
            $query->where('employee_profile_id', $user->id);
        })->where('status', 0)->get();

        

        //return view('Support_Pages.ticketDashboard', compact("tickets"), compact("tickets_closed"), compact("own_tickets"));
        return view('Support_Pages.ticketDashboard', compact("tickets", "tickets_closed", "own_tickets"));
        
    }
    public function filter(Request $request){
        $user = Auth::check() ? Auth::user() : null;

        // $query = Ticket::query();
        // if ($request->has('helpline') && $request->helpline != '') {
        //     $tickets = Ticket::whereDoesntHave('employee_Tickets')->where('status', 0)->where('line', $request->helpline)->get();
        // }else{
        //     $tickets = Ticket::whereDoesntHave('employee_Tickets')->where('status', 0)->get();
        // }

        $query = Ticket::query();

        if ($request->has('helpline') && $request->helpline != '') {
            $query->where('line', $request->helpline);
        }

        if ($request->has('urgency') && $request->urgency != '') {
            $query->where('urgency', $request->urgency);
        }

        

        if ($request->has('sort') && $request->sort != '') {
            switch ($request->sort) {
                case 'created_at_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'created_at_asc':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'urgency_desc':
                    $query->orderBy('urgency', 'desc');
                    break;
                case 'urgency_asc':
                    $query->orderBy('urgency', 'asc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $tickets = $query->where('status', 0)->whereDoesntHave('employee_tickets')->get();


        
        // $own_tickets = Ticket::whereHas('employee_Tickets', function ($query) use ($user) {
        //     $query->where('employee_profile_id', $user->id);
        // })->where('status', 0)->get();

        $own_tickets = Ticket::whereHas('employee_Tickets', function ($query) use ($user) {
            $query->where('employee_profile_id', $user->id);
        })->where('status', 0);

        if ($request->has('filter') && $request->filter === 'own_tickets') {
            if ($request->has('urgency_own') && $request->urgency_own !== null) {
                $own_tickets->where('urgency_own', $request->urgency_own);
            }

            if ($request->has('sort_own')) {
                switch ($request->sort_own) {
                    case 'created_at_asc':
                        //$own_tickets = $own_tickets->orderBy('created_at', 'asc');
                        $own_tickets->orderBy('created_at', 'asc');
                        break;
                    case 'created_at_desc':
                        $own_tickets->orderBy('created_at', 'desc');
                        break;
                    case 'urgency_asc':
                        $own_tickets->orderBy('urgency', 'asc');
                        break;
                    case 'urgency_desc':
                        $own_tickets->orderBy('urgency', 'desc');
                        break;
                }
            }else{
                $own_tickets->orderBy('created_at', 'desc');
            }
        }

        $own_tickets = $own_tickets->get();


        $tickets_closed = Ticket::where('status', 1)->get();

        //return view('Support_Pages.ticketDashboard', compact('tickets', 'own_tickets', 'tickets_closed'));
        return view('Support_Pages.ticketDashboard', compact('tickets', 'own_tickets', 'tickets_closed'))
        ->with('current_filters', $request->all());
    }

    public function assignTicket(Request $request, $ticketid){
        $user = Auth::check() ? Auth::user() : null;
        //$user = 0;
        
        if($user){
            $employeeTicket = new Employee_Ticket();
            $employeeTicket->employee_profile_id = $user->id;
            
            $employeeTicket->ticket_id = $ticketid;
            $employeeTicket->save();
           
        }
        //$ticket = Ticket::find($id);

       return redirect()->route('ticket_dashboard')->with(['employee_tickets' => $employeeTicket]);
    }

    public function unassignTicket(Request $request, $ticketid){
        $user = Auth::check() ? Auth::user() : null;
        if($user){
            
            $employeeTicket = Employee_Ticket::where('employee_profile_id', $user->id)
                                             ->where('ticket_id', $ticketid)
                                             ->first();
    
            
            if($employeeTicket){
                $employeeTicket->delete(); //todo checken of dat zomaar mag gedelete worden
            }
        }
        return redirect()->route('ticket_dashboard')->with(['employee_tickets' => $employeeTicket]);
    }


}