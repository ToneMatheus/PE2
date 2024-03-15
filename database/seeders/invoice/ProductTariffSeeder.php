<?php

namespace Database\Seeders\Invoice;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product_tariff;

class ProductTariffSeeder extends Seeder
{
    public function run(): void
    {
        $productTariffs = [];

        for($i = 1; $i <= 5; $i++){
            $productTariffs[] = [
                'id' => $i,
                'start_date' => '2024-01-01',
                'end_date' => null,
                'product_id' => $i,
                'tariff_id' => $i
            ];
        }
        
        DB::table('product_tariffs')->insert($productTariffs);
    }
}
