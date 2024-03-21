<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index()
    {
        $holidays = [
            ['employee' => 'John Smith', 'duration' => 3],
            ['employee' => 'Emily Johnson', 'duration' => 5],
            ['employee' => 'Michael Williams', 'duration' => 2],
            ['employee' => 'Sarah Brown', 'duration' => 4],
            ['employee' => 'David Lee', 'duration' => 3],
        ];

        return view('holidays.index', compact('holidays'));
    }
}
