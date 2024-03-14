<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManagerController extends Controller
{
    public function action(Request $request){
        if($request->input('accept')){
            return view('managerPage', ['accept' => 1]);
        }
        if($request->input('decline')){
            return view('managerPage', ['decline' => 1]);
        }
    }
}
