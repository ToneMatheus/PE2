<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function invoice_lines(): HasMany
    {
        return $this->hasMany(Invoice_line::class);
    } 

    public function customer_contract(): BelongsTo
    {
        return $this->belongsTo(Customer_contracts::class);
    }

    use HasFactory;
}
