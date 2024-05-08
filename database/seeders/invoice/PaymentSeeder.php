<?php

namespace Database\Seeders\Invoice;

use Illuminate\Database\Seeder;
use App\Models\Payment;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        Payment::create([
            'amount' => 100,
            'payment_date' => '2024-05-08',
            'IBAN' => 'IBAN1',
            'name' => 'John Doe',
            'address' => '123 Main Street, Antwerp, Belgium',
            'structured_communication' => '1234567890'
        ]);

        Payment::create([
            'amount' => 200,
            'payment_date' => '2024-05-09',
            'IBAN' => 'IBAN2',
            'name' => 'Jane Smith',
            'address' => '456 Elm Street, Brussels, Belgium',
            'structured_communication' => '0987654321'
        ]);

        Payment::create([
            'amount' => 105,
            'payment_date' => '2024-05-10',
            'IBAN' => 'IBAN3',
            'name' => 'Alice Johnson',
            'address' => '789 Oak Street, Sint-Niklaas, Belgium',
            'structured_communication' => '5432167890'
        ]);

        Payment::create([
            'amount' => 50,
            'payment_date' => '2024-05-11',
            'IBAN' => 'IBAN4',
            'name' => 'Bob Brown',
            'address' => '101 Pine Street, Asse, Belgium',
            'structured_communication' => '0987123456'
        ]);

        Payment::create([
            'amount' => 99.60,
            'payment_date' => '2024-05-12',
            'IBAN' => 'IBAN5',
            'name' => 'Eve Taylor',
            'address' => '202 Cedar Street, Ghent, Belgium',
            'structured_communication' => '6543210987'
        ]);
    }
}
