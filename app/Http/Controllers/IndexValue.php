<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IndexValue;

class IndexValueController extends Controller
{
    /**
     * Store a newly created index value.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate request data
        $validatedData = $request->validate([
            'value' => 'required|numeric',
            'meter_id' => 'required|exists:meters,id',
        ]);

        // Create index value
        $indexValue = IndexValue::create([
            'value' => $validatedData['value'],
            'meter_id' => $validatedData['meter_id'],
        ]);

        // Return a response, redirect, or anything else you need to do
    }
}
