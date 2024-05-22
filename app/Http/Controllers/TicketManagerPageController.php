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

    const URGENCY_MAPPING = [
        0 => 'Low',
        1 => 'Medium',
        2 => 'High',
    ];

    public function index(Request $request)
    {

        DB::enableQueryLog();
        $status = $request->get('status', 'all');

        $openTickets = Ticket::select(DB::raw("COUNT(*) as count"), DB::raw("DATE(created_at) as date"))
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

        // Round the average closing time to the nearest whole number
        $averageClosingTime = round($averageClosingTime);


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

    

            $teamMembers->each(function ($teamMember) {
                $teamMember->averageClosingTime = DB::table('users')
                    ->join('employee_profiles', 'users.employee_profile_id', '=', 'employee_profiles.id')
                    ->join('employee_tickets', 'employee_profiles.id', '=', 'employee_tickets.employee_profile_id')
                    ->join('tickets', 'employee_tickets.ticket_id', '=', 'tickets.id')
                    ->where('users.id', $teamMember->id)
                    ->where('tickets.status', 1) // 1 for 'closed'
                    ->select(DB::raw("AVG(TIMESTAMPDIFF(HOUR, tickets.created_at, tickets.close_date)) as averageClosingTime"))
                    ->first()
                    ->averageClosingTime;

                if ($teamMember->averageClosingTime) {
                    $teamMember->averageClosingTime = round($teamMember->averageClosingTime);
                } else {
                    $teamMember->averageClosingTime = 0;
                }
            });

            $teamAverageClosingTime = $teamMembers->avg('averageClosingTime');

            if ($teamAverageClosingTime) {
                $teamAverageClosingTime = round($teamAverageClosingTime);
            } else {
                $teamAverageClosingTime = 0;
            }
            
        return view('customertickets/ManagerTicketPage', compact('dates', 'openCounts', 'closedCounts', 'averageClosingTime', 'teamMembers', 'teamAverageClosingTime'));
    }

    public function showTickets()
    {
        $tickets = Ticket::where('status', '<>', 1)
            ->orderBy('created_at', 'asc')
            ->get();

        foreach ($tickets as $ticket) {
            if (array_key_exists($ticket->urgency, self::URGENCY_MAPPING)) {
                $ticket->urgency = self::URGENCY_MAPPING[$ticket->urgency];
            }
        }

        $lines = range(1, 3);
        $urgencies = array_values(self::URGENCY_MAPPING);

        return view('customertickets\ManagerTicketEscalationPage', ['tickets' => $tickets, 'lines' => $lines, 'urgencies' => $urgencies]);
    }

    public function update(Request $request, $id)
    {
        $ticket = Ticket::find($id);

        if ($ticket) {
            $ticket->line = $request->input('line');

            $urgencyMapping = array_flip(self::URGENCY_MAPPING);
            $urgencyInput = $request->input('urgency');

            if (array_key_exists($urgencyInput, $urgencyMapping)) {
                $ticket->urgency = $urgencyMapping[$urgencyInput];
            } else {
                return response()->json(['error' => 'Invalid urgency level'], 400);
            }

            $ticket->save();

            return response()->json(['success' => 'Ticket updated successfully']);
        }

        return response()->json(['error' => 'Ticket not found'], 404);
    }

    public function getTicketsData(Request $request)
    {
        $line = $request->input('line');

        $tickets = Ticket::where('status', '<>', 1)
            ->where('line', $line)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json(['tickets' => $tickets]);
    }
}
