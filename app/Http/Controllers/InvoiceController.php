<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Controllers\EstimationController;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\View;

class InvoiceController extends Controller
{
    public function showButton()
    {
        return view('testingEstimation');
    }
    public function generateAllInvoices()
    {
        return;
    }
    public function generateOneInvoice(Request $request)
    {
        $request->validate([
            'customerID' => 'required|integer|min:1|max:2',
        ], [
            'customerID.min' => 'This field must be at least 1.','customerID.max' => 'There are only 2 customers ATM, sorry!',
        ]);
        $customerID = $request->input('customerID');
        $this->createInvoice($customerID);
    }
    /**
     * @param \Illuminate\Http\Request $request
     * @return int
    */
    private function createInvoice(int $customerID)
    {
        $meterIDs = $this->findMeters($customerID);
        $customerContractID = $this->findCustomerContract($customerID);
        $invoiceID = $this->makeInvoice($customerContractID);
        foreach($meterIDs as $meterID)
        {
            $moneyAmountMonthly = EstimationController::CalculateMonthlyAmount($meterID);
            DB::table('invoice_lines')->insert(array(
                'type' => 'Product',
                'unit_price' => 0.25,
                'amount' => $moneyAmountMonthly,
                'invoice_id' => $invoiceID, ));
        }
        $this->addInvoiceDefault($invoiceID);
        $totalAmount = DB::table('invoice_lines')->where('invoice_id', $invoiceID)->sum('amount');
        DB::table('invoices')->where('id', $invoiceID)->update(array('total_amount' => $totalAmount));
        $this->generatePDF($customerID, $invoiceID, $meterIDs);
    }
    private function generatePDF(int $customerID, int $invoiceID, array $meterIDs)
    {
        $pdf = new Dompdf();
        $customerFirstName = DB::table('customer')->where('ID', $customerID)->value('firstName');
        $customerLastName = DB::table('customer')->where('ID', $customerID)->value('lastName');
        $date = Carbon::today()->format('d/m/Y');;
        $total = DB::table('invoice')->where('ID', $invoiceID)->value('totalAmount');
        $data = [
            'invoiceID' => $invoiceID,
            'date' => $date,
            'firstName' => $customerFirstName,
            'lastName' => $customerLastName,
            'unitPrice' => 0.25,
            'totalPrice' => $total,
        ];
        $counter = 1;
        foreach($meterIDs as $meterID)
        {
            $data['Meter'.($counter)] = $meterID;
            $quantity = EstimationController::CalculateMonthlyAmount($meterID)/0.25;
            $data['Quantity'.$counter] = $quantity;
            $counter+=1;
        }
        $html = View::make('layout', $data)->render();
        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();
        $pdfFileName = 'invoice_' . time() . '.pdf';
        $pdf->stream($pdfFileName);
    }
    private function findMeters(int $customerID): array
    {
        $meterIDs = [];
        $addressIDs = DB::table('customeraddress')->where('customerID', $customerID)->pluck('addressID');
        foreach ($addressIDs as $address) {
    
            $meterIDs[] = DB::table('addressmeter')->where('addressID', $address)->pluck('meterID');
        }
        $meterIDs = collect($meterIDs)->flatten()->all();

        return $meterIDs;
    }
    private function findCustomerContract(int $customerID): int
    {
        $contractID = DB::table('customercontract')->where('customerID', $customerID)->pluck('ID')->toArray();
        $contractID = $contractID[0];
        return $contractID;
    }
    private function makeInvoice(int $customerContractID): int
    {
        $id = DB::table('invoice')->insertGetId(array(
            'invoiceDate' => Carbon::today(),
            'dueDate' => Carbon::today()->addDays(14),
            'totalAmount' => 0,
            'status' => 'New', 
            'customerContractID' => $customerContractID, 
            "type" => "Electrical"));
        return $id;
    }
    private function addInvoiceDefault(int $invoiceID): void
    {
        DB::table('invoiceline')->insert(array(
            'type' => 'Basic Service Fee',
            'unitPrice' => 10.00,
            'amount' => 10.00,
            'invoiceID' => $invoiceID, ));    
        DB::table('invoiceline')->insert(array(
            'type' => 'Distribution Fee',
            'unitPrice' => 10.00,
            'amount' => 10.00,
            'invoiceID' => $invoiceID, ));
    }
}
