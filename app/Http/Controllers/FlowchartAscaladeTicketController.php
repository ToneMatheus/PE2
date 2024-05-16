<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class FlowchartAscaladeTicketController extends Controller
{
    public function index(Request $request): View
    {
        return view('Support_Pages.flowchart.Flowchart-ascalade-ticket');
    }

    public function contract(){
        return view('Support_Pages.flowchart.flowchart-contract');
    }

    public function mkContract(){
        return view('Support_Pages.flowchart.flowchart-make-contract');
    }

    public function problem(){
        return view('Support_Pages.flowchart.flowchart-type-problem');
    }
}
