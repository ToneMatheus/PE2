<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SimpleUserOverViewController extends Controller
{
    public function overview()
    {
        $userData = DB::table('user')->select('username')->get();

        $customerData = DB::table('customer')
            ->join('user', 'customer.userID', '=', 'user.id')
            ->select('customer.lastName', 'customer.firstName')
            ->get();
        
        $addressData = DB::table('customer')
            ->join('customeraddress', 'customer.ID', '=', 'customeraddress.customerID')
            ->join('address', 'customeraddress.addressID', '=', 'address.ID')
            ->select(DB::raw('CONCAT(address.street, ", ", address.number, ", ", address.postalCode, " ", address.city, ", ", address.region) AS address'))
            ->get();
        return view('customer_overview', compact('userData', 'customerData', 'addressData'));
    }
}
