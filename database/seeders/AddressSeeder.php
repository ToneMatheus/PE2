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
                'box' => 2,
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
                'street' => 'Vrijdagmarkt',
                'number' => 22,
                'box' => 5,
                'postal_code' => 9000,
                'city' => 'Ghent',
                'province' => 'East Flanders',
                'country' => 'Belgium',
                'type' => 'house',
                'is_billing_address' => 1,
            ],
            [
                'id' => 4,
                'street' => 'Bondgenotenlaan',
                'number' => 15,
                'box' => 3,
                'postal_code' => 3000,
                'city' => 'Leuven',
                'province' => 'Flemish Brabant',
                'country' => 'Belgium',
                'type' => 'house',
                'is_billing_address' => 0,
            ],
            [
                'id' => 5,
                'street' => 'Meir',
                'number' => 88,
                'box' => 7,
                'postal_code' => 2000,
                'city' => 'Antwerp',
                'province' => 'Antwerp',
                'country' => 'Belgium',
                'type' => 'appartment',
                'is_billing_address' => 1,
            ],
            [
                'id' => 6,
                'street' => 'Mechelsesteenweg',
                'number' => 27,
                'box' => 10,
                'postal_code' => 2550,
                'city' => 'Kontich',
                'province' => 'Antwerp',
                'country' => 'Belgium',
                'type' => 'appartment',
                'is_billing_address' => 1,
            ],
            [
                'id' => 7,
                'street' => 'Bruul',
                'number' => 10,
                'box' => 15,
                'postal_code' => 2800,
                'city' => 'Mechelen',
                'province' => 'Antwerp',
                'country' => 'Belgium',
                'type' => 'appartment',
                'is_billing_address' => 1,
            ],
            [
                'id' => 8,
                'street' => 'Marktplein',
                'number' => 12,
                'box' => 8,
                'postal_code' => 2860,
                'city' => 'Sint-Katelijne-Waver',
                'province' => 'Antwerp',
                'country' => 'Belgium',
                'type' => 'house',
                'is_billing_address' => 1,
            ],
            [
                'id' => 9,
                'street' => 'Marktplein',
                'number' => 33,
                'box' => 8,
                'postal_code' => 2860,
                'city' => 'Sint-Katelijne-Waver',
                'province' => 'Antwerp',
                'country' => 'Belgium',
                'type' => 'house',
                'is_billing_address' => 1,
            ],
            [
                'id' => 10,
                'street' => 'Mechelsesteenweg',
                'number' => 21,
                'box' => 8,
                'postal_code' => 2860,
                'city' => 'Sint-Katelijne-Waver',
                'province' => 'Antwerp',
                'country' => 'Belgium',
                'type' => 'house',
                'is_billing_address' => 1,
            ],
        ]);
    }
}
