<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Invoice;
use Illuminate\Support\Facades\Log;

//this service generates a pdf with a QR code. The QR code holds a link to the invoice payment page for a given invoice
class QRCodeService
{
    public $domain = "http://127.0.0.1:8000"; //change later
    public function PaymentQRCodePdf($invoiceID)
    {
        $invoice = Invoice::findOrFail($invoiceID);
        $hash = md5($invoice->id . $invoice->customer_contract_id . $invoice->meter_id);
        $pdf = Pdf::loadView('Invoices.QRCode_pdf', [
            'domain' => $this->domain,
            'invoiceID' => $invoiceID,
            'hash' => $hash
        ], [], 'utf-8');

        Log::info("QR code generated with link: " . $this->domain . "/pay/" . $invoiceID . "/" . $hash);

        return $pdf->output();
    }
}
