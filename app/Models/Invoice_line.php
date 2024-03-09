<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice_line extends Model
{
    protected $fillable = [
        'type',
        'unit_price',
        'amount',
        'consumption_id',
        'invoice_id'
    ];

    use HasFactory;
}
