<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    public function showTariff(){
        $productTariffs = DB::table('tariff')
        ->join('productTariff', 'tariff.ID', '=', 'productTariff.tariffID')
        ->join('product', 'product.ID', '=', 'productTariff.productID')
        ->whereNull('productTariff.endDate')
        ->get();
        
        return view('tariff', ['productTariffs' => $productTariffs]);
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
}
