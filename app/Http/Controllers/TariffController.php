<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class TariffController extends Controller
{
    public function showTariff(){
        $productTariffs = DB::table('tariffs as t')
        ->join('product_tariffs as pt', 't.ID', '=', 'pt.tariff_id')
        ->join('products as p', 'p.ID', '=', 'pt.product_id')
        ->whereNull('pt.end_date')
        ->get();
        
        return view('tariff', ['productTariffs' => $productTariffs]);
    }

    public function processTariff(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'type' => 'required',
            'rangeMin' => 'required|numeric|min:0',
            'rangeMax' => [
                'nullable',
                'numeric',
                function ($attribute, $value, $fail) use ($request) {
                    $rangeMin = $request->input('rangeMin');
                    if (!is_null($value) && $rangeMin >= $value) {
                        $fail('The maximum value must be greater than the minimum value.');
                    }
                }
            ],
            'rate' => 'required|numeric|min:0.1'
        ]);

        if($validator->fails()){
            return redirect()->route('tariff')->withErrors($validator)->withInput();
        }

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
                'product_name' => $request->input('name'),
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
        $validator = Validator::make($request->all(), [
            'rangeMin' => 'required|numeric|min:0',
            'rangeMax' => [
                'nullable',
                'numeric',
                function ($attribute, $value, $fail) use ($request) {
                    $rangeMin = $request->input('rangeMin');
                    if (!is_null($value) && $rangeMin >= $value) {
                        $fail('The maximum value must be greater than the minimum value.');
                    }
                }
            ],
            'rate' => 'required|numeric|min:0.1'
        ]);

        if($validator->fails()){
            return redirect()->route('tariff')->withErrors($validator)->withInput();
        }

        $tariff = DB::table('tariffs')
        ->where('id', $tID)
        ->first();

        $rangeMin = $request->input('rangeMin');
        $rangeMax = $request->input('rangeMax');
        $rate = $request->input('rate');

        if ($rangeMin !== $tariff->range_min || $rangeMax !== $tariff->range_max || $rate !== $tariff->rate ){
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

    public function getProductByType($type){
        $products = DB::table('products')
        ->where('type', '=', $type)
        ->whereNull('end_date')
        ->orderBy('product_name', 'desc')
        ->first();

        return response()->json($products);
    }
}
