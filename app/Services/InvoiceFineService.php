<?php

namespace App\Services;

use App\Models\Invoice_line;
use App\Models\Invoice;

class InvoiceFineService
{
    public function unpaidInvoiceFine($invoiceID)
    {
        //check if the customercontract has a previously unpaid invoice, 2 weeks after due_date
        //  - find customer_contract_id
        //  - find most recent invoice with a due_date minimum 2 weeks ago
        //  - check status
        //if this is the case, create an invoice line for $invoiceID with fine & return true
        //if this is not the case, return false

        $ccID = Invoice::where('id', $invoiceID)->value('customer_contract_id');
        $status = Invoice::select('status')
            ->where('customer_contract_id', 1)
            ->where('due_date', '<=', now()->subDays(14))
            ->orderBy('invoice_date', 'desc')
            ->limit(1)
            ->value('status');

        if ($status == 'sent')
        {
            Invoice_line::create([
                'type' => 'Unpaid Invoice Fine',
                'unit_price' => 50,
                'amount' => 1,
                'consumption_id' => null,
                'invoice_id' => $invoiceID
            ]);

            return true;
        }
        else 
        {
            return false;
        }
    }
}
