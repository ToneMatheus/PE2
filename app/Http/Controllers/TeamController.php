<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{
    public function index()
    {
        $teamsWithManagers = DB::table('teams')
                ->join('team_members', 'teams.id', '=', 'team_members.team_id')
                ->join('users', 'team_members.user_id', '=', 'users.id')
                ->where('team_members.is_manager', '=', 1)
                ->select('teams.id as teamId', 'teams.team_name as teamName', 'users.first_name', 'users.last_name')
                ->get();

        $allTeams = DB::table('teams')->select('id', 'team_name')->get();

        return view('teamOverview', [
            'teamsWithManagers' => $teamsWithManagers,
            'allTeams' => $allTeams
        ]);
    }

    public function getTeamMembers($teamId)
    {
        $teamMembers = DB::table('team_members')
                ->join('users', 'team_members.user_id', '=', 'users.id')
                ->where('team_members.team_id', '=', $teamId)
                ->select('users.first_name', 'users.last_name')
                ->get();

        return response()->json($teamMembers);
    }

    public function getUsersNotInTeam()
    {
        $usersNotInTeam = DB::table('users')
            ->leftJoin('team_members', 'users.id', '=', 'team_members.user_id')
            ->whereNull('team_members.team_id')
            ->select('users.id', 'users.first_name', 'users.last_name')
            ->get();

        return response()->json($usersNotInTeam);
    }

    public function addMemberToTeam(Request $request)
    {
        $teamId = $request->teamId;
        $userId = $request->userId;

        $memberExists = DB::table('team_members')
                                            ->where('team_id', $teamId)
                                            ->where('user_id', $userId)
                                            ->exists();
        
        if (!$memberExists) {
            DB::table('team_members')->insert([
                'team_id' => $teamId,
                'user_id' => $userId,
                'is_manager' => 0,
                'is_active' => 1
            ]);

            return response()->json(['success' => true, 'message' => 'This is a test response.']);
        }

        return response()->json(['success' => false, 'message' => 'Member already exists in team']);
    }
}
