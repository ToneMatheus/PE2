<?php

namespace App\Http\Controllers;

use App\Charts\IncomeChart;
use App\Models\Invoice;
use App\Models\Invoice_line;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{

    public function fetch(){
        $oldestInvoiceDate = Invoice::orderBy('invoice_date', 'asc')->value('invoice_date');
        $startDate = Carbon::parse($oldestInvoiceDate)->format('Y-m-d');
        $endDate = now()->format('Y-m-d');

        $invoices = Invoice::whereBetween('invoice_date', [$startDate, $endDate])->orderBy('invoice_date')->get();
    
        $invoicesByMonthYear = $invoices->mapToGroups(function ($item, $key) {
            $date = Carbon::parse($item->invoice_date)->format('M Y');
            return [$date => $item->total_amount];
        })->map(function ($group) {
            return $group->sum();
        });

        return response()->json($invoicesByMonthYear);
    }
    //
    public function index(Request $request){
        // Get the date inputs
        $oldestInvoiceDate = Invoice::orderBy('invoice_date', 'asc')->value('invoice_date');
        $startDate = Carbon::parse($oldestInvoiceDate)->format('Y-m-d');
        $endDate = now()->format('Y-m-d');

        if(isset($request->start_date)){
            $startDate = $request->start_date;
        }
        if(isset($request->end_date)){
            $endDate = $request->end_date;
        }

        // Fetch a collection of all invoices between start and end date
        $invoices = Invoice::whereBetween('invoice_date', [$startDate, $endDate])->orderBy('invoice_date')->get();
        $invoice_lines = collect();
        //Prepare chart data
        $graphData = collect();
        //Putting invoice lines into a collection since they dont have a date field
        foreach($invoices as $invoice){
            $invoice_lines->push(Invoice_line::where('invoice_id', $invoice->id)->get());
        }

        $invoicesByMonthYear = $invoices->mapToGroups(function ($item, $key) {
            $date = Carbon::parse($item->invoice_date)->format('M Y');
            return [$date => $item->total_amount];
        })->map(function ($group) {
            return $group->sum();
        });
        $labels = $invoicesByMonthYear->keys()->toArray(); // Extract keys as labels
        $grossIncomes = $invoicesByMonthYear->values()->toArray(); // Extract values as gross incomes

        $totalPotentialGrossIncome = $invoices->sum('total_amount');
        $totalGrossIncome =  $invoices->where('status', 'paid')->sum('total_amount');
        $amountDue = $invoices->where('status', 'sent')->sum('total_amount');

        $invoicesTotalCount = $invoices->count();
        $invoicesUnpaid = $invoices->where('status', 'sent')->count();
        $ratioPaidUnpaid =  100-($invoicesUnpaid/$invoicesTotalCount*100); // Calculate the ratio between paid and unpaid invoices
        $totalSoldElectricity = $invoice_lines->collapse()->where('type', 'Electricity')->sum('amount');

        return view('statistics.index', compact('totalGrossIncome', 'totalPotentialGrossIncome', 'amountDue', 'ratioPaidUnpaid', 'totalSoldElectricity', 'startDate', 'endDate'));
    }
}
