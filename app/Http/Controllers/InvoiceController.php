<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceMail;
use App\Models\Invoice;
use App\Models\Invoice_line;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class InvoiceController extends Controller
{
    //
    public function sendMail(Invoice $invoice)
    {
        $user = User::where('id', $invoice->user_id)->first();
        if ($invoice != null) {
            //finnvc99@gmail.com is going to be replaced with: $user->email
            Mail::to('finnvc99@gmail.com')->send(new InvoiceMail($invoice, $user->name));
        }
        return redirect()->intended('dashboard');
    }
}
