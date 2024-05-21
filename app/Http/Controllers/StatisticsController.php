<?php

namespace App\Http\Controllers;

use App\Charts\IncomeChart;
use App\Models\Customer_contracts;
use App\Models\Invoice;
use App\Models\Invoice_line;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function index(Request $request, IncomeChart $chart){
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
        $values = $invoicesByMonthYear->values()->toArray(); // Extract values as gross incomes
        
        $totalPotentialGrossIncome = $invoices->sum('total_amount');
        $totalGrossIncome =  $invoices->where('status', 'paid')->sum('total_amount');
        $amountDue = $invoices->where('status', 'sent')->sum('total_amount');

        $invoicesTotalCount = $invoices->count();
        $invoicesUnpaid = $invoices->where('status', 'sent')->count();
        $ratioPaidUnpaid =  100-($invoicesUnpaid/$invoicesTotalCount*100); // Calculate the ratio between paid and unpaid invoices
        $totalSoldElectricity = $invoice_lines->collapse()->where('type', 'Electricity')->sum('amount');

        //CREDIT SCORE CALCULATIONS
        function calculateLatePaymentPenalty($days_overdue) {
            // Calculate the penalty points based on the number of days overdue
            return min(floor($days_overdue / 14), 6) * 3;
        }

        // Load all customer contracts with their invoices and payments, and include the user
        $contracts = Customer_contracts::with(['invoices.payments', 'user'])->get();
        $userCreditScores = collect();

        foreach ($contracts as $contract) {
            $user = $contract->user;
            if (!$user) {
                continue; // Skip if the contract has no associated user
            }

            // Initialize credit score for each user once
            if (!$userCreditScores->contains('user.id', $user->id)) {
                $userCreditScores->push(['user' => $user, 'credit_score' => 100]);
            }
            // Calculate the penalty for this contract
            $penalty = 0;

            foreach ($contract->invoices as $invoice) {
                // Check for late payments
                foreach ($invoice->payments as $payment) {
                    if ($payment->payment_date > $invoice->due_date) {
                        $due_date = Carbon::parse($invoice->due_date);
                        $payment_date = Carbon::parse($payment->payment_date);
                        $days_overdue = $payment_date->diffInDays($due_date, true);
                        $penalty += calculateLatePaymentPenalty($days_overdue);
                    }
                }

                // Check for overdue invoices without payments
                if ($invoice->payments->isEmpty() && Carbon::parse($invoice->due_date)->isPast()) {
                    $due_date = Carbon::parse($invoice->due_date);
                    $days_overdue = Carbon::now()->diffInDays($due_date, true);
                    $penalty += calculateLatePaymentPenalty($days_overdue);
                }
            }

            // Update the user's credit score in the collection
            $userCreditScores = $userCreditScores->map(function ($item) use ($user, $penalty) {
                if ($item['user']->id == $user->id) {
                    $item['credit_score'] = max(0, $item['credit_score'] - $penalty); // Ensure the credit score does not go below 0
                }
                return $item;
            });
        }

        // Sort the collection by credit score, from low to high
        $userCreditScores = $userCreditScores->sortBy('credit_score');

        // Optionally, you can reset the keys to get a simple array of sorted results
        $userCreditScores = $userCreditScores->values();



        return view('statistics.index', ['chart' => $chart->build($labels, $values, $startDate, $endDate)], compact('totalGrossIncome', 'totalPotentialGrossIncome', 'amountDue', 'ratioPaidUnpaid', 'totalSoldElectricity', 'startDate', 'endDate', 'userCreditScores'));
    }
}
