<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use Illuminate\Support\Facades\Mail;
use App\Mail\weekAdvanceReminder;
use App\Mail\InvoiceDue;
use App\Mail\InvoiceFinalWarning;

use App\Models\Invoice_line;
use App\Models\User;

use App\Mail\MonthlyInvoiceMail;

use Illuminate\Support\Facades\DB;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

use App\Services\InvoiceFineService;

use App\Jobs\WeekAdvanceReminderJob;

class advancemailcontroller extends Controller
{
    public function index()
    {
        WeekAdvanceReminderJob::dispatch();
    }
}
