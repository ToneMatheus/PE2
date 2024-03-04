<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContractProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('contractProduct')->insert([
            [
                'ID' => 1,
                'startDate' => '2024-01-01',
                'customerContractID' => 1,
                'ProductID' => 1,
                'TariffID' => 5,
            ],
            ]);
    }
}
