<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meter;

class MeterController extends Controller
{
    function getData()
    {
        return Meter::all();
    }
}
