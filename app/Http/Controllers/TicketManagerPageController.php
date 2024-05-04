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

        DB::enableQueryLog();
        $status = $request->get('status', 'all');

        $openTickets = Ticket::where('status', 0) // 0 for 'open'
            ->select(DB::raw("COUNT(*) as count"), DB::raw("DATE(created_at) as date"))
            ->groupBy(DB::raw("DATE(created_at)"))
            ->get();

        $closedTickets = Ticket::where('status', 1) // 1 for 'closed'
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

        $averageClosingTime = Ticket::where('status', 1) // 1 for 'closed'
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
            ->select('users.id', 'users.first_name', 'users.last_name', 'team_members.is_active')
            ->get();


        $teamMembers->each(function ($teamMember) {
            $teamMember->tickets = DB::table('users')
                ->join('employee_profiles', 'users.employee_profile_id', '=', 'employee_profiles.id')
                ->join('employee_tickets', 'employee_profiles.id', '=', 'employee_tickets.employee_profile_id')
                ->join('tickets', 'employee_tickets.ticket_id', '=', 'tickets.id')
                ->where('users.id', $teamMember->id)
                ->select('tickets.status', DB::raw('COUNT(*) as count'), DB::raw('SUM(case when tickets.status = 0 then 1 else 0 end) as unsolved'), DB::raw('SUM(case when tickets.status = 1 then 1 else 0 end) as solved'))
                ->groupBy('tickets.status')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->status => ['count' => $item->count, 'unsolved' => $item->unsolved, 'solved' => $item->solved]];
                });
        });
        

        return view('customertickets/ManagerTicketPage', compact('dates', 'openCounts', 'closedCounts', 'averageClosingTime', 'teamMembers'));
    }

}