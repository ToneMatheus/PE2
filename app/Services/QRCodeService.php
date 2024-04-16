<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;

//this service generates a pdf with a QR code. The QR code holds a link to the invoice payment page for a given invoice
class QRCodeService
{
    public $domain = "http://127.0.0.1:8000"; //change later
    public function PaymentQRCodePdf($invoiceID)
    {
        $pdf = Pdf::loadView('Invoices.QRCode_pdf', [
            'domain' => $this->domain,
            'invoiceID' => $invoiceID
        ], [], 'utf-8');

        return $pdf->output();
    }
}
