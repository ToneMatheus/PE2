<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TicketManagerPageController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');

        $openTickets = Ticket::where('status', 'open')
            ->select(DB::raw("COUNT(*) as count"), DB::raw("DATE(created_at) as date"))
            ->groupBy(DB::raw("DATE(created_at)"))
            ->get();

        $closedTickets = Ticket::where('status', 'closed')
            ->select(DB::raw("COUNT(*) as count"), DB::raw("DATE(created_at) as date"))
            ->groupBy(DB::raw("DATE(created_at)"))
            ->get();

        $dates = $openTickets->pluck('date');
        $openCounts = $openTickets->pluck('count');
        $closedCounts = $closedTickets->pluck('count');

        return view('customertickets/ManagerTicketPage', compact('dates', 'openCounts', 'closedCounts'));
    }
}
