<?php

namespace App\Services;
use Illuminate\Support\Facades\Log;
use App\Models\Invoice;

class StructuredCommunicationService
{
    protected $now;

    public function generate($invoiceID)
    {
        //to generate the structured communication, the invoice id is used as base number
        //as check, we use the modulo 97 algorithm

        //format base number
        $baseNum = (string)$invoiceID;
        $fBaseNum = "";

        $addZero = 11 - strlen($baseNum);
        
        for ($i = 0; $i < $addZero; $i++)
        {
            $fBaseNum .= "0";
        }
        $fBaseNum .= (string)$baseNum;

        //calculation of check digit
        $rem = $invoiceID % 97;
        $checkDigit = 97 - $rem;

        //format check digit
        if ($checkDigit < 10)
            $fCheckDigit = "0" . $checkDigit;
        else
            $fCheckDigit = $checkDigit;

        //concatenate numbers and return code
        $fCode = $this->format($fBaseNum . $fCheckDigit);
        //Log::info("Generated structured communication: " . $fCode);
        return $fCode;
    }

    public function addStructuredCommunication($structuredCom, $invoiceID)
    {
        //add a structured communication to an invoice
        $invoiceEdit = Invoice::find($invoiceID);
        $invoiceEdit->structured_communication = $structuredCom;
        $invoiceEdit->save();
    }

    private function format($code) 
    {
        $group1 = substr($code, 0, 3);
        $group2 = substr($code, 3, 4);
        $group3 = substr($code, 7);
        $fCode = "+++" . $group1 . "/" . $group2 . "/" . $group3 . "+++";
        
        return $fCode;
    }
}
