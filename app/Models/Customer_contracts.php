<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer_contracts extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'start_date',
        'end_date',
        'type',
        'price',
        'status'
    ];
//meerdere contract products

    public function contract_products(): HasMany
    {
        return $this->hasMany(Contract_product::class,'id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    use HasFactory;
}
