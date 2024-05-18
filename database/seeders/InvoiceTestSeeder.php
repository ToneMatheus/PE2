<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Index_Value;
use App\Models\Consumption;
use App\Models\Meter;

class InvoiceTestSeeder extends Seeder
{
    public function run()
    {
        $indexID = Index_Value::insertGetId([
            'meter_id' => 2,
            'reading_date' => '2025-01-17',
            'reading_value' => '6700',
        ]);

        Consumption::insert([
            'start_date' => '2024-01-01',
            'end_date' => '2025-01-17',
            'consumption_value' => 100,
            'prev_index_id' => 6,
            'current_index_id' => $indexID
        ]);
    }
}
