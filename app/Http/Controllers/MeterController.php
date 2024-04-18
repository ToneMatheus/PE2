<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meter;

class MeterController extends Controller
{
    public function showMeters()
    {
        $data = Meter::all();

        return view('Meters/meters', compact('data'));
    }
}
