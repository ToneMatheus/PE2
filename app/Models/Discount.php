<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'contract_product_id',
        'range_min',
        'range_max',
        'rate',
        'start_date',
        'end_date'
    ];

    use HasFactory;
}
