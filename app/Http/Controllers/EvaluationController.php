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

    $teamMembers = DB::table('team_members')
        ->join('users', 'team_members.user_id', '=', 'users.id')
        ->where('team_members.team_id', $teamId)
        ->where('team_members.is_manager', 0)
        ->select('users.id', 'users.first_name', 'users.last_name', 'team_members.is_active')
        ->get();

    // Assuming these are just placeholders for now
    $averageClosingTime = 24; // Example static data

    return view('HR_EmployeeProfile.evaluations', compact('teamMembers', 'averageClosingTime'));
}

    public function managerTicketPage()
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
            $teamMember->tickets = DB::table('users')
                ->join('employee_profiles', 'users.employee_profile_id', '=', 'employee_profiles.id')
                ->join('employee_tickets', 'employee_profiles.id', '=', 'employee_tickets.employee_profile_id')
                ->join('tickets', 'employee_tickets.ticket_id', '=', 'tickets.id')
                ->where('users.id', $teamMember->id)
                ->select('tickets.status', DB::raw('COUNT(*) as count'))
                ->groupBy('tickets.status')
                ->get()
                ->mapWithKeys(function ($item) 
                {
                    return [$item->status => $item->count];
                });
        }
    
        // Assuming these are just placeholders for now
        $averageClosingTime = 24; // Example static data
    
        return view('customertickets.ManagerTicketPage', compact('teamMembers', 'averageClosingTime'));
    }    
}
?>