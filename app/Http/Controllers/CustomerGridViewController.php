<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class CustomerGridViewController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort');
        $search = $request->get('search');
        $query = DB::table('customer')
                    ->join('customercontract', 'customer.userID', '=', 'customercontract.customerID')
                    ->select('customer.id', 'customer.lastName', 'customer.firstName', 'customer.phoneNumber', 'customer.companyName', 'customer.isCompany', 'customer.userID', 'CustomerContract.startdate', 'CustomerContract.enddate', 'CustomerContract.type', 'CustomerContract.price');

        if ($search) {
                 $query->where('firstName', 'like', "%{$search}%")
                       ->orWhere('lastName', 'like', "%{$search}%")
                       ->orWhere('companyName', 'like', "%{$search}%")
                       ->orWhere('userID', 'like', "%{$search}%");
            }

        if ($sort) {
            $query->orderBy($sort);
        }

        $customers = $query->paginate(10);

        return view('CustomerGridView', ['customers' => $customers]);
    }
}