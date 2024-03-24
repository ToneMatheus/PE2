<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{
    public function index()
    {
        $teams = DB::table('teams')
        ->join('team_members', 'teams.id', '=', 'team_members.team_id')
        ->join('users', 'team_members.user_id', '=', 'users.id')
        ->select('teams.team_name as teamName', 'users.first_name', 'users.last_name')
        ->where('team_members.is_manager', 1)
        ->get();

        return view('teamOverview', ['teams' => $teams]);
    }
}
