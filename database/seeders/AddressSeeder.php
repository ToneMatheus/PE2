<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Address;

class AddressSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('addresses')->insert([
            [
                'id' => 1,
                'street' => 'Main Street',
                'number' => 123,
                'box' => 'A',
                'postal_code' => 1000,
                'city' => 'Brussels',
                'province' => 'Brussels',
                'country' => 'Belgium',
                'type' => 'house',
                'is_billing_address' => 1,
            ],
            [
                'id' => 2,
                'street' => 'Oak Avenue',
                'number' => 456,
                'box' => 'B',
                'postal_code' => 2000,
                'city' => 'Antwerp',
                'province' => 'Antwerp',
                'country' => 'Belgium',
                'type' => 'appartment',
                'is_billing_address' => 0,
            ],
            [
                'id' => 3,
                'street' => 'Pine Street',
                'number' => 789,
                'box' => 'C',
                'postal_code' => 3000,
                'city' => 'Ghent',
                'province' => 'East Flanders',
                'country' => 'Belgium',
                'type' => 'house',
                'is_billing_address' => 1,
            ],
            [
                'id' => 4,
                'street' => 'Cedar Lane',
                'number' => 101,
                'box' => 'D',
                'postal_code' => 4000,
                'city' => 'Leuven',
                'province' => 'Flemish Brabant',
                'country' => 'Belgium',
                'type' => 'house',
                'is_billing_address' => 0,
            ],
            [
                'id' => 5,
                'street' => 'Birch Avenue',
                'number' => 121,
                'box' => 'E',
                'postal_code' => 5000,
                'city' => 'Mechelen',
                'province' => 'Antwerp',
                'country' => 'Belgium',
                'type' => 'appartment',
                'is_billing_address' => 1,
            ],
            [
                'id' => 6,
                'street' => 'Main Street',
                'number' => 121,
                'box' => 'E',
                'postal_code' => 2550,
                'city' => 'Kontich',
                'province' => 'Antwerp',
                'country' => 'Belgium',
                'type' => 'appartment',
                'is_billing_address' => 1,
            ],
        ]);
    }
}
