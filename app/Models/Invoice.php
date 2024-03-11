<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_date',
        'due_date',
        'total_amount',
        'status',
        'customer_contract_id',
        'type'
    ];

    use HasFactory;
}
