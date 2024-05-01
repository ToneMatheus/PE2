<?php

namespace Database\Seeders\Invoice;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CreditNote;

class CreditNoteSeeder extends Seeder
{
    public function run()
    {
        CreditNote::insert([
            'status' => 1,
            'amount' => 50,
            'invoice_id' => 48,
            'user_id' => 5,
            'is_applied' => 1,
        ]);
    }
}
