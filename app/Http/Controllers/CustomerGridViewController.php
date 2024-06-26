<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer_Address;

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
                ->join('customer_contracts', 'users.id', '=', 'customer_contracts.user_id')
                ->select('users.id', 'users.username', 'users.first_name', 'users.last_name', 'users.phone_nbr', 'users.is_company', 'users.company_name', 'users.email', 'users.birth_date', 'users.is_active', 'customer_contracts.start_date', 'customer_contracts.end_date', 'customer_contracts.type', 'customer_contracts.price', 'customer_contracts.status');

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

        $customers = $query->paginate(10);

        return view('Customers/CustomerGridView', ['customers' => $customers, 'sort' => $sort, 'direction' => $direction]);
    }


    public function edit($id)
    {
        $date = Carbon::now()->toDateString();

        $customer = DB::table('users')->where('id', $id)->first();

        $customerContract = DB::table('customer_contracts')
        ->where('user_id', '=', $id)
        ->first();

        $contractProducts = DB::table('contract_products as cp')
        ->select('cp.id as cpID', 'cp.start_date as cpStartDate', 'p.product_name as productName',
        'p.id as pID', 'm.id as mID', 'p.*', 't.*', 'a.*', 'cp.customer_contract_id as cID')
        ->join('products as p', 'p.id', '=', 'cp.product_id')
        ->join('meters as m', 'm.id', '=', 'cp.meter_id')
        ->join('meter_addresses as ma', 'ma.meter_id', '=', 'm.id')
        ->join('addresses as a', 'a.id', '=', 'ma.address_id')
        ->join('product_tariffs as pt', 'pt.product_id', '=', 'p.id')
        ->join('tariffs as t', 't.id', '=', 'pt.id')
        ->where('customer_contract_id', '=', $customerContract->id)
        ->whereNull('cp.end_date')
        ->get();

        $products = DB::table('products')
        ->whereNull('end_date')
        ->get();

        $types = DB::table('products as p')
        ->select('p.type')
        ->distinct()
        ->whereNull('end_date')
        ->get();

        $discounts = [];

        foreach($contractProducts as $contractProduct){
            $discountQuery = DB::table('discounts')
            ->where('contract_product_id', '=', $contractProduct->cpID)
            ->whereDate('end_date', '>', $date)
            ->first();

            if ($discountQuery) {
                $discounts[] = $discountQuery;
            }
        }
        return view('Customers/CustomerEdit', ['customer' => $customer, 'cps' => $contractProducts, 'types' => $types, 'products' => $products, 'discounts' => $discounts]); /*'discount' => $discount*/
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

    public function updateContractProduct(Request $request, $id, $oldCpID, $cID, $mID){
        if(!is_null($request->input('percentage'))){
            $validator = Validator::make($request->all(), [
                'percentage' => 'required|numeric|min:1.8',
                'startDate' => 'required|date',
                'endDate' => [
                    'required',
                    'date',
                    function ($attribute, $value, $fail) use ($request) {
                        $startDate = $request->input('startDate');
                        if ($startDate >= $value) {
                            $fail('The end date should be later than the start date');
                        }
                    }
                ]
            ]);

            if($validator->fails()){
                return redirect()->route('customer.edit', ['id' => $id])->withErrors($validator)->withInput();
            }
    
            $date = Carbon::now()->toDateString();
    
            $tariffID = DB::table('discounts')->insertGetId([
                'contract_product_id' => $oldCpID,
                'rate' => $request->input('newRate'),
                'start_date' => $request->input('startDate'),
                'end_date' => $request->input('endDate')
            ]);
        } else {
            $date = Carbon::now()->toDateString();

            DB::update('UPDATE contract_products SET end_date = ? WHERE id = ?', [$date, $oldCpID]);
    
            DB::table('contract_products')->insert([
                'customer_contract_id' => $cID,
                'product_id' => $request->input('product'),
                'start_date' => $date,
                'end_date' => null,
                'meter_id' => $mID
            ]);
    
            //Remove discount that belongs to old contract_product
            DB::table('discounts')
            ->where('contract_product_id', $oldCpID)
            ->whereDate('end_date', '>', $date)
            ->update(['end_date' => $date]);
        }

        return redirect('/customerGridView');
    }

    public function addDiscount(Request $request, $cpID, $id){
        $validator = Validator::make($request->all(), [
            'percentage' => 'required|numeric|min:1.8',
            'startDate' => 'required|date',
            'endDate' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    $startDate = $request->input('startDate');
                    if ($startDate >= $value) {
                        $fail('The end date should be later than the start date');
                    }
                }
            ]
        ]);

        if($validator->fails()){
            return redirect()->route('customer.edit', ['id' => $id])->withErrors($validator)->withInput();
        }

        $date = Carbon::now()->toDateString();

        $tariffID = DB::table('discounts')->insertGetId([
            'contract_product_id' => $cpID,
            'rate' => $request->input('newRate'),
            'start_date' => $request->input('startDate'),
            'end_date' => $request->input('endDate')
        ]);

        return redirect()->route('customer.edit', ['id' => $id]);
    }

    public function getProductsByType($type){
        $products = DB::table('products')
        ->where('type', '=', $type)
        ->whereNull('end_date')
        ->get();

        return response()->json($products);
    }
}