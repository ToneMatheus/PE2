<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    public function showTariff(){
        $productTariffs = DB::table('tariff as t')
        ->join('productTariff as pt', 't.ID', '=', 'pt.tariffID')
        ->join('product as p', 'p.ID', '=', 'pt.productID')
        ->whereNull('pt.endDate')
        ->get();

        $contractProducts = DB::table('contractProduct as cp')
        ->join('customerContract as cc', 'cc.ID', '=', 'cp.customerContractID')
        ->join('customer as c', 'c.ID', '=', 'cc.customerID')
        ->join('product as p', 'p.ID', '=', 'cp.productID')
        ->leftJoin('tariff as t', 't.ID', '=', 'cp.tariffID')
        ->whereNull('cp.endDate')
        ->select(DB::raw("
            cp.ID,
            cp.customerContractID,
            CONCAT(c.firstName, ' ', c.lastName) as name,
            p.productName,
            CASE
                WHEN cp.tariffID IS NULL THEN p.type
                ELSE t.type
            END AS type,
            CASE
                WHEN cp.tariffID IS NULL THEN NULL
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

            $tariffID = DB::table('tariff')->insertGetId([
                'type' => $request->input('type'),
                'rangeMin' => $request->input('rangeMin'),
                'rangeMax' => $rangeMax,
                'rate' => $request->input('rate'),
            ]);

            $productID = DB::table('product')->insertGetId([
                'productName' => $request->input('name'),
                'startDate' => Carbon::now()->toDateString(),
                'type' => $request->input('type'),
            ]);

            DB::table('productTariff')->insert([
                'startDate' => Carbon::now()->toDateString(),
                'productID' => $productID,
                'tariffID' => $tariffID
            ]);
        }

        return redirect()->route('tariff');
    }

    public function inactivateTariff($pID, $tID){
        $date = Carbon::now()->toDateString();

        DB::update('UPDATE product SET endDate = ? WHERE ID = ? ', [$date, $pID]);
        DB::update('UPDATE productTariff SET endDate = ? WHERE productID = ? AND tariffID = ?', [$date, $pID, $tID]);
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
                $newtID = DB::table('tariff')->insertGetId([
                    'type' => $tariff->type,
                    'rangeMin' => $rangeMin,
                    'rangeMax' => $rangeMax,
                    'rate' => $rate
                ]);

                $date = Carbon::now()->toDateString();
                DB::update('UPDATE productTariff SET endDate = ? WHERE productID = ? AND tariffID = ?', [$date, $pID, $tID]);
                DB::table('productTariff')->insert([
                    'startDate' => $date,
                    'productID' => $pID,
                    'tariffID' => $newtID
                ]);
            }
            return redirect()->route('tariff');
        }
    }

    public function showContractProduct($cpID){
        $contractProduct = DB::table('contractProduct as cp')
        ->join('customerContract as cc', 'cc.ID', '=', 'cp.customerContractID')
        ->join('customer as c', 'c.ID', '=', 'cc.customerID')
        ->join('product as p', 'p.ID', '=', 'cp.productID')
        ->where('cp.ID', '=', $cpID)
        ->first();

        if (!$contractProduct) {
            abort(404);
        }

        $products = DB::table('product as p')
        ->select('p.productName', 'p.type')
        ->whereNotIn('p.type', ['Discount'])
        ->get();

        $productTariff = DB::table('product as p')
        ->join('productTariff as pt', 'pt.productID', '=', 'p.ID')
        ->join('tariff as t', 't.ID', '=', 'pt.ID')
        ->where('p.ID' ,'=', $contractProduct->productID)
        ->first();

        if($contractProduct->tariffID){
            $discount = DB::table('tariff')
            ->where('ID', '=', $contractProduct->tariffID)
            ->first();

            return view('contractProduct', ['contractProduct' => $contractProduct, 'productTariff' => $productTariff, 'products' => $products,  'discount' => $discount]);
        }

        return view('contractProduct', ['contractProduct' => $contractProduct, 'productTariff' => $productTariff, 'products' => $products,]);
    }

    public function addDiscount(Request $request, $cpID, $ccID, $pID){
        $date = Carbon::now()->toDateString();

        DB::update('UPDATE contractProduct SET endDate = ? WHERE ID = ?', [$date, $cpID]);

        $tariffID = DB::table('tariff')->insertGetId([
            'type' => 'Discount',
            'rate' => $request->input('newRate')
        ]);

        $newCpID = DB::table('contractProduct')->insertGetId([
            'productID' => $pID,
            'tariffID' => $tariffID,
            'customerContractID' => $ccID,
            'startDate' => $date
        ]);

        return redirect()->route('contractProduct', ['cpID' => $newCpID]);
    }
}
