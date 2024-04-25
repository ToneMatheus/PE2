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

        if ($status == 'all') {
            $tickets = Ticket::select(DB::raw("COUNT(*) as count"), DB::raw("DATE(created_at) as date"))
                ->groupBy(DB::raw("DATE(created_at)"))
                ->get();
        } else {
            $tickets = Ticket::where('status', $status)
                ->select(DB::raw("COUNT(*) as count"), DB::raw("DATE(created_at) as date"))
                ->groupBy(DB::raw("DATE(created_at)"))
                ->get();
        }

        $dates = $tickets->pluck('date');
        $counts = $tickets->pluck('count');

        return view('customertickets/ManagerTicketPage', compact('dates', 'counts'));
    }
}
