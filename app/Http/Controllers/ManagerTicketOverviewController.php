<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ManagerTicketOverview;
use Illuminate\Support\Facades\Auth;

class ManagerTicketOverviewController extends Controller
{
    public function index(Request $request)
    {
        // Retrieve the desired locale from the request or any other source
        $locale = $request->input('locale', 'en_US'); // Example: Default to 'en_US' if not provided in the request

        // Set the application locale dynamically
        app()->setLocale($locale);

        // Your controller logic here
        $tickets = ManagerTicketOverview::all();
        return view('managerticketoverview.index', compact('tickets'));
    }
}
