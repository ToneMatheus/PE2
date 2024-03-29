<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{
    public function index()
    {
        $teamsWithManagers = DB::table('teams')
        ->join('team_members', 'teams.id', '=', 'team_members.team_id')
        ->join('users', 'team_members.user_id', '=', 'users.id')
        ->select('teams.team_name as teamName', 'users.first_name', 'users.last_name')
        ->where('team_members.is_manager', 1)
        ->get();

        $allTeams = Team::all();

        // $teamManagers = $teamsWithManagers->reduce(function ($carry, $item) {
        //     $carry[$item->teamId] = $item->first_name . ' ' . $item->last_name;
        //     return $carry;
        // }, []);

        return view('teamOverview', [
            'teams' => $teamsWithManagers,
            'allTeams' => $allTeams, 
            // 'teamManagers' => $teamManagers,
        ]);
    }

    // public function addTeam(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'team_name' => 'required|string|max:255',
    //     ]);

    //     $team = new Team;
    //     $team->team_name = $validatedData['team_name'];
    //     $team->save();

    //     return response()->json(['success' => true]);
    // }
}
