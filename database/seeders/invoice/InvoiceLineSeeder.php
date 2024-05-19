<?php

namespace Database\Seeders\Invoice;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice_line;

class InvoiceLineSeeder extends Seeder
{
    public function run(): void
    {
        for($i = 1; $i <= 17; $i++){
            DB::table('invoice_lines')->insert([
                'type' => 'Electricity',
                'unit_price' => 0.25,
                'amount' => 30.00,
                'consumption_id' => null,
                'invoice_id' => $i,
            ]);
    
            DB::table('invoice_lines')->insert([
                'type' => 'Basic Service Fee',
                'unit_price' => 10,
                'amount' => 10,
                'consumption_id' => null,
                'invoice_id' => $i,
            ]);
    
            DB::table('invoice_lines')->insert([
                'type' => 'Distribution Fee',
                'unit_price' => 10,
                'amount' => 10,
                'consumption_id' => null,
                'invoice_id' => $i,
            ]);
        }

        for($i = 18; $i <= 28; $i++){
            DB::table('invoice_lines')->insert([
                'type' => 'Electricity',
                'unit_price' => 0.28,
                'amount' => 93.75,
                'consumption_id' => null,
                'invoice_id' => $i,
            ]);
    
            DB::table('invoice_lines')->insert([
                'type' => 'Basic Service Fee',
                'unit_price' => 10,
                'amount' => 10,
                'consumption_id' => null,
                'invoice_id' => $i,
            ]);
    
            DB::table('invoice_lines')->insert([
                'type' => 'Distribution Fee',
                'unit_price' => 10,
                'amount' => 10,
                'consumption_id' => null,
                'invoice_id' => $i,
            ]);
        }

        for($i = 29; $i <= 39; $i++){
            DB::table('invoice_lines')->insert([
                'type' => 'Electricity',
                'unit_price' => 0.25,
                'amount' => 435.4,
                'consumption_id' => null,
                'invoice_id' => $i,
            ]);
    
            DB::table('invoice_lines')->insert([
                'type' => 'Basic Service Fee',
                'unit_price' => 10,
                'amount' => 10,
                'consumption_id' => null,
                'invoice_id' => $i,
            ]);
    
            DB::table('invoice_lines')->insert([
                'type' => 'Distribution Fee',
                'unit_price' => 10,
                'amount' => 10,
                'consumption_id' => null,
                'invoice_id' => $i,
            ]);
        }

        for($i = 40; $i <= 50; $i++){
            DB::table('invoice_lines')->insert([
                'type' => 'Electricity',
                'unit_price' => 0.25,
                'amount' => 88.75,
                'consumption_id' => null,
                'invoice_id' => $i,
            ]);
    
            DB::table('invoice_lines')->insert([
                'type' => 'Basic Service Fee',
                'unit_price' => 10,
                'amount' => 10,
                'consumption_id' => null,
                'invoice_id' => $i,
            ]);
    
            DB::table('invoice_lines')->insert([
                'type' => 'Distribution Fee',
                'unit_price' => 10,
                'amount' => 10,
                'consumption_id' => null,
                'invoice_id' => $i,
            ]);
        }

        for($i = 51; $i <= 61; $i++){
            DB::table('invoice_lines')->insert([
                'type' => 'Electricity',
                'unit_price' => 0.25,
                'amount' => 79.8,
                'consumption_id' => null,
                'invoice_id' => $i,
            ]);
    
            DB::table('invoice_lines')->insert([
                'type' => 'Basic Service Fee',
                'unit_price' => 10,
                'amount' => 10,
                'consumption_id' => null,
                'invoice_id' => $i,
            ]);
    
            DB::table('invoice_lines')->insert([
                'type' => 'Distribution Fee',
                'unit_price' => 10,
                'amount' => 10,
                'consumption_id' => null,
                'invoice_id' => $i,
            ]);

        for($i = 62; $i <= 120; $i++){
            DB::table('invoice_lines')->insert([
                'type' => 'Electricity',
                'unit_price' => 0.25,
                'amount' => 93.75,
                'consumption_id' => null,
                'invoice_id' => $i,
            ]);
    
            DB::table('invoice_lines')->insert([
                'type' => 'Basic Service Fee',
                'unit_price' => 10,
                'amount' => 10,
                'consumption_id' => null,
                'invoice_id' => $i,
            ]);
    
            DB::table('invoice_lines')->insert([
                'type' => 'Distribution Fee',
                'unit_price' => 10,
                'amount' => 10,
                'consumption_id' => null,
                'invoice_id' => $i,
            ]);
        }

        for($i = 121; $i <= 142; $i++){
            DB::table('invoice_lines')->insert([
                'type' => 'Electricity',
                'unit_price' => 0.25,
                'amount' => 435.4,
                'consumption_id' => null,
                'invoice_id' => $i,
            ]);
    
            DB::table('invoice_lines')->insert([
                'type' => 'Basic Service Fee',
                'unit_price' => 10,
                'amount' => 10,
                'consumption_id' => null,
                'invoice_id' => $i,
            ]);
    
            DB::table('invoice_lines')->insert([
                'type' => 'Distribution Fee',
                'unit_price' => 10,
                'amount' => 10,
                'consumption_id' => null,
                'invoice_id' => $i,
            ]);
        }

        for($i = 143; $i <= 164; $i++){
            DB::table('invoice_lines')->insert([
                'type' => 'Electricity',
                'unit_price' => 0.25,
                'amount' => 88.75,
                'consumption_id' => null,
                'invoice_id' => $i,
            ]);
    
            DB::table('invoice_lines')->insert([
                'type' => 'Basic Service Fee',
                'unit_price' => 10,
                'amount' => 10,
                'consumption_id' => null,
                'invoice_id' => $i,
            ]);
    
            DB::table('invoice_lines')->insert([
                'type' => 'Distribution Fee',
                'unit_price' => 10,
                'amount' => 10,
                'consumption_id' => null,
                'invoice_id' => $i,
            ]);
        }

        for($i = 165; $i <= 197; $i++){
            DB::table('invoice_lines')->insert([
                'type' => 'Electricity',
                'unit_price' => 0.25,
                'amount' => 79.8,
                'consumption_id' => null,
                'invoice_id' => $i,
            ]);
    
            DB::table('invoice_lines')->insert([
                'type' => 'Basic Service Fee',
                'unit_price' => 10,
                'amount' => 10,
                'consumption_id' => null,
                'invoice_id' => $i,
            ]);
    
            DB::table('invoice_lines')->insert([
                'type' => 'Distribution Fee',
                'unit_price' => 10,
                'amount' => 10,
                'consumption_id' => null,
                'invoice_id' => $i,
            ]);
        }

        DB::table('invoice_lines')->insert([
            'type' => 'Electricity',
            'unit_price' => 0.25,
            'amount' => 79.8,
            'consumption_id' => null,
            'invoice_id' => 198,
        ]);

        DB::table('invoice_lines')->insert([
            'type' => 'Basic Service Fee',
            'unit_price' => 10,
            'amount' => 10,
            'consumption_id' => null,
            'invoice_id' => 198,
        ]);

        DB::table('invoice_lines')->insert([
            'type' => 'Distribution Fee',
            'unit_price' => 10,
            'amount' => 10,
            'consumption_id' => null,
            'invoice_id' => 198,
        ]);

        DB::table('invoice_lines')->insert([
            'type' => 'Electricity',
            'unit_price' => 0.25,
            'amount' => 79.8,
            'consumption_id' => null,
            'invoice_id' => 199,
        ]);

        DB::table('invoice_lines')->insert([
            'type' => 'Basic Service Fee',
            'unit_price' => 10,
            'amount' => 10,
            'consumption_id' => null,
            'invoice_id' => 199,
        ]);

        DB::table('invoice_lines')->insert([
            'type' => 'Distribution Fee',
            'unit_price' => 10,
            'amount' => 10,
            'consumption_id' => null,
            'invoice_id' => 199,
        ]);
    }
}
}