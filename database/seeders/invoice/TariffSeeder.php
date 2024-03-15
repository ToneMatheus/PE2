<?php

namespace Database\Seeders\Invoice;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Tariff;

class TariffSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tariffs')->insert([
            [
                'id' => 1,
                'type' => 'Residential',
                'range_min' => 0,
                'range_max' => 500,
                'rate' => 0.25,
            ],
            [
                'id' => 2,
                'type' => 'Residential',
                'range_min' => 500,
                'range_max' => 1000,
                'rate' => 0.28,
            ],
            [
                'id' => 3,
                'type' => 'Residential',
                'range_min' => 1001,
                'range_max' => null,
                'rate' => 0.32,
            ],
            [
                'id' => 4,
                'type' => 'Commercial',
                'range_min' => 0,
                'range_max' => 1000,
                'rate' => 0.25,
            ],
            [
                'id' => 5,
                'type' => 'Commercial',
                'range_min' => 1001,
                'range_max' => 2000,
                'rate' => 0.28,
            ],
        ]);
    }
}

?>