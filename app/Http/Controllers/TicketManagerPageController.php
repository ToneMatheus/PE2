<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Team_Member;
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
            ->select(DB::raw("COUNT(*) as count"), DB::raw("DATE(close_date) as date"))
            ->groupBy(DB::raw("DATE(close_date)"))
            ->get();

        $dates = $openTickets->concat($closedTickets)->pluck('date')->unique();

        $openCounts = $openTickets->mapWithKeys(function ($item) {
            return [$item['date'] => $item['count']];
        });

        $closedCounts = $closedTickets->mapWithKeys(function ($item) {
            return [$item['date'] => $item['count']];
        });

        $averageClosingTime = Ticket::where('status', 'closed')
        ->select(DB::raw("AVG(TIMESTAMPDIFF(SECOND, created_at, close_date)) as averageClosingTime"))
        ->first()
        ->averageClosingTime;

        // Convert the average closing time from seconds to hours
        $averageClosingTime = $averageClosingTime / 3600;

        $userId = Auth::id();

        $teamId = Team_Member::where('user_id', $userId)
            ->where('is_manager', 1)
            ->value('team_id');

        $teamMembers = DB::table('team_members')
            ->join('users', 'team_members.user_id', '=', 'users.id')
            ->where('team_members.team_id', $teamId)
            ->where('team_members.is_manager', 0)
            ->select('users.first_name', 'users.last_name', 'team_members.is_active')
            ->get();

        return view('customertickets/ManagerTicketPage', compact('dates', 'openCounts', 'closedCounts', 'averageClosingTime', 'teamMembers'));
    }

}