<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveRequest;

class LeaveRequestController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'reason' => 'required',
            'description' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $leaveRequest = new LeaveRequest();
        $leaveRequest->reason = $validatedData['reason'];
        $leaveRequest->description = $validatedData['description'];
        $leaveRequest->start_date = $validatedData['start_date'];
        $leaveRequest->end_date = $validatedData['end_date'];
        $leaveRequest->save();

        return redirect()->back()->with('success', 'Leave request submitted successfully.');
    }
}
