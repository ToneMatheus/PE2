<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Invoice_line;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    //
    public function index(){
        $electricitySold = Invoice_line::where('type', 'Electricity')->sum('amount');
        $gasSold = 20000; // Example gas sold in m³

        // Calculate current income (sum of paid invoices)
        $currentIncome = Invoice::where('status', 'paid')->sum('total_amount');

        // Calculate potential income (sum of all invoices)
        $potentialIncome = Invoice::sum('total_amount');

        // Pass data to the view
        return view('statistics.index', compact('electricitySold', 'gasSold', 'currentIncome', 'potentialIncome'));
    }
}
