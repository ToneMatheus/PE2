<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contract_product extends Model
{
    protected $fillable = [
        'customer_contract_id',
        'tariff_id',
        'product_id',
        'start_date',
        'end_date'
    ];

    public function discounts(): HasMany
    {
        return $this->hasMany(Discount::class);
    }  
    
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function tariff(): BelongsTo
    {
        return $this->belongsTo(Tariff::class);
    }
    
    public function customer_contract(): BelongsTo
    {
        return $this->belongsTo(Customer_contracts::class, 'customer_contract_id');
    }

    use HasFactory;
}
