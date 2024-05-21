<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class holidayRequest extends Controller
{
    //
    // public function __invoke()
    // {
    //         return view('request', [
    //             'user' => auth()->user()
    //         ]);
    // }

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user_id = auth()->user()->employee_profile_id;
        $user_name = auth()->user()->username;
        return view('holidayRequestPage', compact('user_name', 'user_id'));
    }
}
