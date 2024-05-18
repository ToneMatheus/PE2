<?php

namespace Database\Seeders\Invoice;

use Illuminate\Database\Seeder;
use App\Models\Payment;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        Payment::create([
            'amount' => 455.40,
            'payment_date' => '2024-11-27',
            'IBAN' => 'BE23852725036213',
            'name' => 'Jonathan Doe',
            'address' => 'Bontgenotenlaan 15 3 3000 Leuven',
            'structured_communication' => '+++000/0000/002572+++',
            'has_matched' => 1,
            'invoice_id' => 25
        ]);

        Payment::create([
            'amount' => 99.80,
            'payment_date' => '2024-07-20',
            'IBAN' => 'BE23772625035880',
            'name' => 'Ann Doe',
            'address' => 'Mechelsesteenweg 27 10 2550 Kontich',
            'structured_communication' => '',
            'has_matched' => 1,
            'invoice_id' => 43
        ]);

        Payment::create([
            'amount' => 105,
            'payment_date' => '2024-10-10',
            'IBAN' => 'BE23365225039637',
            'name' => 'Alice Johnson',
            'address' => 'Eikenstraat 23 9111 Sint-Niklaas',
            'structured_communication' => '+++543/2167/258890+++',
            'has_matched' => 0,
            'invoice_id' => null
        ]);

        Payment::create([
            'amount' => 50,
            'payment_date' => '2024-10-22',
            'IBAN' => 'BE23336825034442',
            'name' => 'Bob Brown',
            'address' => 'Appelstraat 196 1800 Vilvoorde',
            'structured_communication' => '',
            'has_matched' => 0,
            'invoice_id' => null
        ]);

        Payment::create([
            'amount' => 99.80,
            'payment_date' => '2024-10-26',
            'IBAN' => '',
            'name' => 'Eve Taylor',
            'address' => 'Merenlaan 122 1850 Grimbergen',
            'structured_communication' => '+++000/0000/004651+++',
            'has_matched' => 1,
            'invoice_id' => 46
        ]);
    }
}
