<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use function PHPSTORM_META\map;

class EmployeeController extends Controller
{
    public function showTariff(){
        $productTariffs = DB::table('tariffs as t')
        ->join('product_tariffs as pt', 't.ID', '=', 'pt.tariff_id')
        ->join('products as p', 'p.ID', '=', 'pt.product_id')
        ->whereNull('pt.end_date')
        ->get();

        $contractProducts = DB::table('contract_products as cp')
        ->join('customer_contracts as cc', 'cc.id', '=', 'cp.customer_contract_id')
        ->join('users as u', 'u.id', '=', 'cc.user_id')
        ->join('products as p', 'p.id', '=', 'cp.product_id')
        ->leftJoin('tariffs as t', 't.id', '=', 'cp.tariff_id')
        ->whereNull('cp.end_date')
        ->select(DB::raw("
            cp.id,
            cp.customer_contract_id,
            CONCAT(u.first_name, ' ', u.last_name) as name,
            p.product_name,
            CASE
                WHEN cp.tariff_id IS NULL THEN p.type
                ELSE t.type
            END AS type,
            CASE
                WHEN cp.tariff_id IS NULL THEN NULL
                ELSE t.rate
            END AS rate
        "))
        ->get();
        
        return view('tariff', ['productTariffs' => $productTariffs, 'contractProducts' => $contractProducts]);
    }

    public function processTariff(Request $request){
        if($request->has('submitTariff')){
            $rangeMax = $request->input('rangeMax');

            if(empty($rangeMax)){
                $rangeMax = null;
            }

            $tariffID = DB::table('tariffs')->insertGetId([
                'type' => $request->input('type'),
                'range_min' => $request->input('rangeMin'),
                'range_max' => $rangeMax,
                'rate' => $request->input('rate'),
            ]);

            $productID = DB::table('products')->insertGetId([
                'product_mame' => $request->input('name'),
                'start_date' => Carbon::now()->toDateString(),
                'type' => $request->input('type'),
            ]);

            DB::table('product_tariffs')->insert([
                'start_date' => Carbon::now()->toDateString(),
                'product_id' => $productID,
                'tariff_id' => $tariffID
            ]);
        }

        return redirect()->route('tariff');
    }

    public function inactivateTariff($pID, $tID){
        $date = Carbon::now()->toDateString();

        DB::update('UPDATE products SET end_date = ? WHERE id = ? ', [$date, $pID]);
        DB::update('UPDATE product_tariffs SET end_date = ? WHERE product_id = ? AND tariff_id = ?', [$date, $pID, $tID]);
        return redirect()->route('tariff');
    }

    public function editTariff(Request $request, $pID, $tID){
        if($request->input('submitChangeTariff')){
            $tariff = DB::table('tariff')
            ->where('ID', $tID)
            ->first();

            $rangeMin = $request->input('rangeMin');
            $rangeMax = $request->input('rangeMax');
            $rate = $request->input('rate');

            if ($rangeMin !== $tariff->rangeMin || $rangeMax !== $tariff->rangeMax || $rate !== $tariff->rate ){
                $newtID = DB::table('tariffs')->insertGetId([
                    'type' => $tariff->type,
                    'range_min' => $rangeMin,
                    'range_max' => $rangeMax,
                    'rate' => $rate
                ]);

                $date = Carbon::now()->toDateString();
                DB::update('UPDATE product_tariffs SET end_date = ? WHERE product_id = ? AND tariff_id = ?', [$date, $pID, $tID]);
                DB::table('product_tariffs')->insert([
                    'start_date' => $date,
                    'product_id' => $pID,
                    'tariff_id' => $newtID
                ]);
            }
            return redirect()->route('tariff');
        }
    }

    public function showContractProduct($cpID){
        $date = Carbon::now()->toDateString();
        
        $contractProduct = DB::table('contract_products as cp')
        ->select('cp.id as cpID', 'cp.start_date as cpStartDate', 'p.product_name as productName',
        'p.id as pID', 't.id as tID', 'cc.id as ccID','u.*', 'u.id as uID')
        ->join('customer_contracts as cc', 'cc.ID', '=', 'cp.customer_contract_id')
        ->join('users as u', 'u.id', '=', 'cc.user_id')
        ->join('products as p', 'p.id', '=', 'cp.product_id')
        ->leftjoin('tariffs as t', 't.id', '=', 'cp.tariff_id')
        ->where('cp.id', '=', $cpID)
        ->whereNull('cp.end_date')
        ->first();

        if (!$contractProduct) {
            abort(404);
        }

        $types = DB::table('products as p')
        ->select('p.type')
        ->distinct()
        ->whereNull('end_date')
        ->get();

        $productTariff = DB::table('products as p')
        ->join('product_tariffs as pt', 'pt.product_id', '=', 'p.id')
        ->join('tariffs as t', 't.id', '=', 'pt.id')
        ->where('p.id', '=', $contractProduct->pID)
        ->first();

        $discount = DB::table('discounts')
        ->where('contract_product_id', '=', $contractProduct->cpID)
        ->whereDate('end_date', '>', $date)
        ->first();

        return view('contractProduct', ['contractProduct' => $contractProduct, 'productTariff' => $productTariff, 'types' => $types,  'discount' => $discount]);

        //return view('contractProduct', ['contractProduct' => $contractProduct, 'productTariff' => $productTariff, 'types' => $types,]);
    }

    public function addDiscount(Request $request, $cpID){
        $date = Carbon::now()->toDateString();

        $tariffID = DB::table('discounts')->insertGetId([
            'contract_product_id' => $cpID,
            'rate' => $request->input('newRate'),
            'start_date' => $request->input('startDate'),
            'end_date' => $request->input('endDate')
        ]);

        return redirect()->route('contractProduct', ['cpID' => $cpID]);
    }

    public function editContractProduct(Request $request, $oldCpID){
        $date = Carbon::now()->toDateString();

        $oldCP = DB::table('contract_products')
        ->where('id', '=', $oldCpID)
        ->first();

        DB::update('UPDATE contract_products SET end_date = ? WHERE id = ?', [$date, $oldCP->id]);

        $newCpID = DB::table('contract_products')->insertGetId([
            'customer_contract_id' => $oldCP->customer_contract_id,
            'product_id' => $request->input('product'),
            'tariff_id' => null,
            'start_date' => $date,
            'end_date' => null
        ]);

        $discount = DB::table('discounts')
        ->where('contract_product_id', '=', $oldCP->id)
        ->whereDate('end_date', '>', $date)
        ->first();

        DB::update('UPDATE discounts SET end_date = ? WHERE id = ?', [$date, $discount->id]);

        return redirect()->route('contractProduct', ['cpID' => $newCpID]);
    }

    public function getProductsByType($type){
        $products = DB::table('products')
        ->where('type', '=', $type)
        ->whereNull('end_date')
        ->get();

        return response()->json($products);
    }
}
