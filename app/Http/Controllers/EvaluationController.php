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
    
        if (!$teamId) {
            abort(404, 'Team not found or you are not a manager.');
        }
    
        $this->updateScoresForAllEmployees();
    
        $teamMembers = DB::table('team_members')
            ->join('users', 'team_members.user_id', '=', 'users.id')
            ->join('employee_profiles', 'users.employee_profile_id', '=', 'employee_profiles.id')
            ->where('team_members.team_id', $teamId)
            ->where('team_members.is_manager', 0)
            ->select('users.id', 'users.first_name', 'users.last_name', 'employee_profiles.score', 'team_members.is_active')
            ->get();
    
        $averageClosingTime = 24; // placeholder
    
        return view('HR_EmployeeProfile.evaluations', compact('teamMembers', 'averageClosingTime'));
    }
    

    private function updateScoresForAllEmployees()
    {
        $allEmployees = DB::table('users')
            ->join('employee_profiles', 'users.employee_profile_id', '=', 'employee_profiles.id')
            ->select('employee_profiles.id as profile_id', 'users.id as user_id')
            ->get();
    
        foreach ($allEmployees as $employee) {
            $tickets = DB::table('tickets')
                ->join('employee_tickets', 'tickets.id', '=', 'employee_tickets.ticket_id')
                ->where('employee_tickets.employee_profile_id', $employee->profile_id)
                ->get();
    
            $closedTickets = $tickets->where('status', 1)->count();
            $avgResolveDays = $tickets->where('status', 1)->avg('resolve_time') ?? 0;
    
            $expectedResolveTime = 5; 
            $expectedClosedTickets = 10;
    
            $baseScore = 5;
            $scoreAdjustment = ($closedTickets / $expectedClosedTickets) * 5;
            $timePenalty = max(0, ($avgResolveDays - $expectedResolveTime) / $expectedResolveTime) * 5;
    
            $newScore = min(10, max(0, $baseScore + $scoreAdjustment - $timePenalty));
    
            DB::table('employee_profiles')
                ->where('id', $employee->profile_id)
                ->update(['score' => $newScore]);
        }
    }    
}
?>