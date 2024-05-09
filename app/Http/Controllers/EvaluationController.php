<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EvaluationController extends Controller
{    
    public function evaluations()
{
        $userId = Auth::id();
        $teamId = DB::table('team_members')
            ->where('user_id', $userId)
            ->where('is_manager', 1)
            ->value('team_id');
    
        if (!$teamId)
        {
            abort(404, 'Team not found or you are not a manager.');
        }
    
        $teamMembers = DB::table('team_members')
            ->join('users', 'team_members.user_id', '=', 'users.id')
            ->where('team_members.team_id', $teamId)
            ->where('team_members.is_manager', 0)
            ->select('users.id', 'users.first_name', 'users.last_name', 'team_members.is_active')
            ->get();
    
        foreach ($teamMembers as $teamMember)
        {
            $teamMember->score = 5;

            $tickets = DB::table('tickets')
                ->join('employee_tickets', 'tickets.id', '=', 'employee_tickets.ticket_id')
                ->join('employee_profiles', 'employee_tickets.employee_profile_id', '=', 'employee_profiles.id')
                ->where('employee_profiles.id', $teamMember->id)
                ->get();

            $closedTickets = $tickets->where('status', 1)->count();
            $avgResolveDays = $tickets->where('status', 1)->avg('resolve_time');

            $expectedResolveTime = 5;
            $expectedClosedTickets = 10; 

            $scoreAdjustment = ($closedTickets / $expectedClosedTickets) * 5;
            $timePenalty = max(0, ($avgResolveDays - $expectedResolveTime) / $expectedResolveTime) * 5;

            $teamMember->score = min(10, max(0, $teamMember->score + $scoreAdjustment - $timePenalty));
        }
    
        $averageClosingTime = 24; // placeholder
    
        return view('HR_EmployeeProfile.evaluations', compact('teamMembers', 'averageClosingTime'));
    }    
}
?>