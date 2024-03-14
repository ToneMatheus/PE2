<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class CustomerGridViewController extends Controller
{
    public function index(Request $request)
    {

    $request->validate([
        'search' => 'nullable|max:255',
        'sort' => 'nullable|alpha_dash',
        'direction' => 'nullable|in:asc,desc',
    ]);

    $sort = $request->get('sort');
    $direction = $request->get('direction', 'asc');
    $search = $request->get('search');
    $query = DB::table('users')
                ->leftJoin('customer_contracts', 'users.id', '=', 'customer_contracts.user_id')
                ->select('users.id', 'users.username', 'users.first_name', 'users.last_name', 'users.phone_nbr', 'users.is_company', 'users.company_name', 'users.email', 'users.birth_date', 'users.is_activate', 'customer_contracts.start_date', 'customer_contracts.end_date', 'customer_contracts.type', 'customer_contracts.price', 'customer_contracts.status');

        if ($search) {
                 $query->where('users.first_name', 'like', "%{$search}%")
                       ->orWhere('users.last_name', 'like', "%{$search}%")
                       ->orWhere('users.company_name', 'like', "%{$search}%")
                       ->orWhere('users.id', 'like', "%{$search}%")
                       ->orWhere('users.email', 'like', "%{$search}%");
            }

            if (!empty($sort)) {
                $query->orderBy($sort, $direction);
            } else {
                $query->orderBy('users.id', $direction);
            }

        $users = $query->paginate(10);

        return view('Customers/CustomerGridView', ['customers' => $users, 'sort' => $sort, 'direction' => $direction]);
    }


    public function edit($id)
    {
        $customer = DB::table('users')->where('id', $id)->first();
        return view('Customers/CustomerEdit', ['customer' => $customer]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'last_name' => 'required|max:255',
            'first_name' => 'required|max:255',
            'phone_nbr' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'company_name' => 'max:255',
        ]);

        DB::table('users')
        ->where('id', $id)
        ->update($request->only('last_name', 'first_name', 'phone_nbr', 'company_name'));

        return redirect('/customerGridView');
    }
}
