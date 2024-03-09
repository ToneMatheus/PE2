<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract_product extends Model
{
    protected $fillable = [
        'customer_contract_id',
        'tariff_id',
        'product_id',
        'start_date',
        'end_date'
    ];

    use HasFactory;
}
